<?php

namespace App\Http\Controllers\Web\Monitoring;

use App\Exports\KomisiExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Request;

class KomisiController extends Controller
{
    protected $table = 'pegawai';

    private $total_layanan = 0;
    private $total_komisi = 0;

    public function index()
    {
        return view('monitoring.employee.fee.index', [
            'js' => ['s-home/monitoring/employee/fee/js/fee.js'],
            'attribute' => [
                'm_mntrg' => 'true',
                'menu_comm_terap' => 'active menu-open',
                'title_bc' => 'Monitoring Terapis',
                'desc_bc' => 'Digunakan untuk media menampilkan komisi pegawai',
            ]
        ]);
    }

    public function show($id, Request $request)
    {
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
                'pegawai',
                'pegawai.id',
                '=',
                'transaksi_detail.pegawai_id'
            )
            ->where('transaksi_detail.pegawai_id', $id)
            ->where('transaksi.status_pembayaran', 'terbayar')
            ->where('pegawai.role', 3);

        if (!empty($request->starts) && !empty($request->ends)) {
            $data->whereBetween(DB::RAW('DATE(transaksi.created_at)'), [$request->starts, $request->ends]);
        }

        $data->select(
            DB::raw('IF((SELECT MIN(id) FROM transaksi_detail where transaksi_id = transaksi.id AND pegawai_id = ' . $id . ' limit 1) = transaksi_detail.id,transaksi.no_transaksi,"") as no_transaksi'),
            'transaksi_detail.paket_id',
            'paket.nama as paket',
            'layanan.nama',
            'transaksi_detail.harga',
            'pegawai.komisi',
            DB::raw('(transaksi_detail.harga*pegawai.komisi/100) as sub_komisi'),
            DB::raw("(SELECT IF(layanan_id, COUNT(layanan_id), 0) FROM transaksi_detail join transaksi ON transaksi.id = transaksi_detail.transaksi_id where " . (
                (!empty($request->starts) && !empty($request->ends)) ?
                "DATE(transaksi.created_at) BETWEEN '" . $request->starts . "' AND '" . $request->ends . "' AND " : '') . " transaksi.status_pembayaran = 'terbayar' AND pegawai_id = '" . $id . "') AS total_layanan"),
            DB::raw("(SELECT IF(harga, SUM(harga*"
                . $this->table . ".komisi/100), 0) FROM transaksi_detail join transaksi ON transaksi.id = transaksi_detail.transaksi_id where "
                . (
                    (!empty($request->starts) && !empty($request->ends)) ?
                    "DATE(transaksi.created_at) BETWEEN '" . $request->starts . "' AND '" . $request->ends . "' AND " : '') . " transaksi.status_pembayaran = 'terbayar' AND pegawai_id = "
                . $this->table . ".id) AS total_komisi")
        )->orderBy('transaksi.id', 'DESC');

        $dataJson = $data->get();

        $this->total_layanan += $dataJson[0]->total_layanan;
        $this->total_komisi += $dataJson[0]->total_komisi;

        if (request()->ajax()) {
            return datatables()->of($data)
                ->addIndexColumn()
                ->with('total_layanan', $this->total_layanan)
                ->with('total_komisi', $this->total_komisi)
                ->make(true);
        }
    }

    public function _data()
    {
        return view('monitoring.employee.fee.content.data.table');
    }

    public function _json(Request $request)
    {
        if (request()->ajax()) {
            $data = DB::table($this->table)
                ->select(
                    $this->table . '.id',
                    $this->table . '.foto',
                    $this->table . '.nama',
                    $this->table . '.jabatan',
                    DB::raw("CONCAT(" . $this->table . '.komisi, " %") as upah'),
                    DB::raw("(SELECT IF(harga, SUM(harga*"
                        . $this->table . ".komisi/100), 0) FROM transaksi_detail join transaksi ON transaksi.id = transaksi_detail.transaksi_id where "
                        . (
                            (!empty($request->starts) && !empty($request->ends)) ?
                            "DATE(transaksi.created_at) BETWEEN '" . $request->starts . "' AND '" . $request->ends . "' AND " : '') . " transaksi.status_pembayaran = 'terbayar' AND pegawai_id = "
                        . $this->table . ".id) AS total_komisi")
                );

            if (!empty(session('cabang_session'))) {
                $data->where('pegawai.cabang_id', session('cabang_session'));
            }

            if (!empty(session('cabang_id'))) {
                $data->where('pegawai.cabang_id', base64_decode(session('cabang_id')));
            }

            $data->where('role', 3)->get();

            return datatables()->of($data)
                ->addColumn('action', 'monitoring/employee/fee/content/data/action_button')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new KomisiExport, 'komisi.xlsx');
    }
}
