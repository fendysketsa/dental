<?php

namespace App\Http\Controllers\Web\Monitoring;

use App\Exports\SalesExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PenjualanModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PenjualanController extends Controller
{
    protected $table_detail = 'transaksi_detail';
    protected $table_member = 'member';

    public function index()
    {
        return view('monitoring.penjualan.index', [
            'js' => [
                's-home/monitoring/penjualan/js/penjualan.js'
            ],
            'attribute' => [
                'm_mntrg' => 'true',
                'menu_mntrg_sale' => 'active menu-open',
                'title_bc' => 'Monitoring - Penjualan',
                'desc_bc' => 'Digunakan untuk media menampilkan penjualan',
            ],
        ]);
    }

    public function show($id, Request $request)
    {
        if ($request->det == 'layanan') {

            $data = DB::table('transaksi_detail')
                ->leftJoin(
                    'transaksi',
                    'transaksi.id',
                    '=',
                    'transaksi_detail.transaksi_id'
                )
                ->leftJoin(
                    'layanan',
                    'layanan.id',
                    '=',
                    'transaksi_detail.layanan_id'
                )
                ->leftJoin(
                    'paket',
                    'paket.id',
                    '=',
                    'transaksi_detail.paket_id'
                )
                ->leftJoin(
                    'member',
                    'member.id',
                    '=',
                    'transaksi.member_id'
                )
                ->where('transaksi.id', $id)
                ->whereNull('transaksi_detail.produk_id')
                ->select(
                    DB::raw('IF((SELECT MIN(td.id)
                    FROM transaksi_detail td
                    LEFT JOIN transaksi t ON t.id = td.transaksi_id
                    WHERE td.transaksi_id = transaksi_detail.id
                    AND t.id = ' . $id . ' limit 1) = transaksi_detail.id, transaksi.no_transaksi ,"") as an_transaksi'),
                    'transaksi.created_at as tanggal',
                    'transaksi.no_transaksi',
                    DB::raw('CONCAT("'
                        . asset("storage/master-data/service/") . '", "/", layanan.gambar) as gambar'),
                    'layanan.nama',
                    'transaksi_detail.harga as harga'
                )
                ->orderBy('transaksi.id', 'DESC')
                ->get();

            if (request()->ajax()) {
                return datatables()->of($data)
                    ->addIndexColumn()
                    ->make(true);
            }
        }

        if ($request->det == 'produk') {

            $data = DB::table('transaksi_detail')
                ->leftJoin(
                    'transaksi',
                    'transaksi.id',
                    '=',
                    'transaksi_detail.transaksi_id'
                )
                ->leftJoin(
                    'produk',
                    'produk.id',
                    '=',
                    'transaksi_detail.produk_id'
                )
                ->leftJoin(
                    'member',
                    'member.id',
                    '=',
                    'transaksi.member_id'
                )
                ->where('transaksi.member_id', $id)
                ->whereNull('transaksi_detail.layanan_id')
                ->select(
                    DB::raw('IF((SELECT MIN(td.id)
                    FROM transaksi_detail td
                    LEFT JOIN transaksi t ON t.id = td.transaksi_id
                    WHERE td.transaksi_id = transaksi_detail.id
                    AND t.member_id = ' . $id . ' limit 1) = transaksi_detail.id, transaksi.no_transaksi ,"") as an_transaksi'),
                    'transaksi.created_at as tanggal',
                    'transaksi.no_transaksi',
                    DB::raw('CONCAT("'
                        . asset("storage/master-data/product/") . '", "/", produk.gambar) as gambar'),
                    'produk.nama as nama',
                    'transaksi_detail.harga as harga'
                )
                ->orderBy('transaksi.id', 'DESC')
                ->get();

            if (request()->ajax()) {
                return datatables()->of($data)
                    ->addIndexColumn()
                    ->make(true);
            }
        }
    }

    public function _data()
    {
        return view('monitoring.penjualan.content.data.table');
    }

    public function _json(Request $request)
    {
        if (request()->ajax()) {
            $data_ = PenjualanModel::where('transaksi.status', '>', 0)
                ->leftJoin(
                    $this->table_detail,
                    $this->table_detail . '.transaksi_id',
                    '=',
                    'transaksi.id'
                )->leftJoin(
                    $this->table_member,
                    $this->table_member . '.id',
                    '=',
                    'transaksi.member_id'
                )->select(
                    'transaksi.id',
                    'transaksi.tanggal_bayar as tanggal',
                    DB::raw('IF(transaksi.cara_bayar_kasir = 1, "Cash", "Card") as cara_bayar'),
                    'member.nama as nama',
                    'transaksi.total_biaya as tagihan',
                    'transaksi.no_transaksi as transaksi',
                    'transaksi.status as status_transaksi'
                )
                ->where('transaksi.status_pembayaran', 'terbayar')
                ->where('transaksi.status', 3);

            if (!empty(session('cabang_session'))) {
                $data_->where('transaksi.lokasi_id', session('cabang_session'));
            } else if (!empty(session('cabang_id'))) {
                $data_->where('transaksi.lokasi_id', base64_decode(session('cabang_id')));
            }

            if (!empty($request->starts) && !empty($request->ends)) {
                $data_->whereBetween(
                    DB::raw('DATE(transaksi.tanggal_bayar)'),
                    [$request->starts, $request->ends]
                );
            }

            $data_->groupBy('id')
                ->orderBy('id', 'DESC')
                ->get();

            return datatables()->of($data_)
                ->addColumn('status_transaksi', 'monitoring.penjualan.content.data.status_transaksi')
                ->addColumn('action', 'monitoring.penjualan.content.data.action_button')
                ->rawColumns(['status_transaksi', 'action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new SalesExport, 'penjualan.xlsx');
    }
}