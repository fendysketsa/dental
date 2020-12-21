<?php

namespace App\Http\Controllers\Web\Information;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends Controller
{
    protected $table = 'transaksi';
    protected $table_detail = 'transaksi_detail';
    protected $table_member = 'member';
    protected $table_pegawai = 'pegawai';

    public function index()
    {
        return view('information.payment.index', [
            'js' => [
                's-home/dist/js/sprintf.js',
                'https://cdn.jsdelivr.net/npm/recta/dist/recta.js',
                's-home/information/payment/js/payment.js'
            ],
            'attribute' => [
                'm_info' => 'true',
                'menu_inf_payment' => 'active menu-open',
                'title_bc' => 'Informasi - Pembayaran',
                'desc_bc' => 'Digunakan untuk media menampilkan informasi , status pembayaran',
            ]
        ]);
    }

    public function show($id)
    {
        if (!empty($id)) :
            $dataTrans = DB::table($this->table)
                ->where('id', $id)->get();

            $dataLayanan = DB::table($this->table_detail)
                ->select(
                    DB::raw('GROUP_CONCAT(IF(layanan_id, layanan_id, 0)) as layanan'),
                    DB::raw('GROUP_CONCAT(IF(pegawai_id, pegawai_id, 0)) as terapis')
                )
                ->whereNull('paket_id')
                ->where('transaksi_id', $id)->get();

            $dataProduk = DB::table($this->table_detail)
                ->select(
                    DB::raw('GROUP_CONCAT(IF(produk_id, produk_id, 0)) as produk'),
                    DB::raw('GROUP_CONCAT(IF(kuantitas, kuantitas, 0)) as jumlah'),
                    DB::raw('GROUP_CONCAT(IF(harga, harga, 0)) as harga')
                )
                ->whereNotNull('produk_id')
                ->where('transaksi_id', $id)->get();

            $dataPaketLayanan = DB::table($this->table_detail)
                ->select(
                    DB::raw('GROUP_CONCAT(IF(pegawai_id, pegawai_id, 0)) as terapis')
                )
                ->whereNotNull('paket_id')
                ->where('transaksi_id', $id)
                ->groupBy('posisi')
                ->orderBy('posisi', 'ASC')
                ->orderBy('id', 'ASC')
                ->get();

            $dataPaketLayananPosisi = DB::table($this->table_detail)
                ->select(
                    DB::raw('DISTINCT(posisi) as posisi')
                )
                ->whereNotNull('paket_id')
                ->where('transaksi_id', $id)->get();

            $dataPaket = DB::table($this->table_detail)
                ->select(
                    DB::raw('GROUP_CONCAT(DISTINCT(paket_id)) as paket')
                )
                ->whereNotNull('paket_id')
                ->where('transaksi_id', $id)->get();

        endif;

        return view('information.payment.content.detail.modal.detail', [
            'data' => !empty($id) ? $dataTrans : null,
            'services' => !empty($id) ? $dataLayanan : null,
            'produk' => !empty($id) ? $dataProduk : null,
            'posisi' => !empty($id) ? $dataPaketLayananPosisi : null,
            'pktservices' => !empty($id) ? $dataPaketLayanan : null,
            'paket' => !empty($id) ? $dataPaket : null,
        ]);
    }

    public function _data()
    {
        return view('information.payment.content.data.table');
    }

    public function _json(Request $request)
    {
        $data_ = DB::table($this->table)
            ->leftJoin(
                $this->table_member,
                $this->table_member . '.id',
                '=',
                $this->table . '.member_id'
            )
            ->leftJoin(
                $this->table_detail,
                $this->table_detail . '.transaksi_id',
                '=',
                $this->table . '.id'
            )
            ->select(
                $this->table . '.agent',
                DB::raw('IF(' . $this->table . '.status = 3, "Lunas" ,"Belum") as lunas'),
                $this->table . '.id',
                $this->table . '.no_transaksi',
                $this->table . '.waktu_reservasi',
                $this->table . '.created_at',
                $this->table . '.tanggal_bayar as waktu',
                $this->table_member . '.nama as nama_member',
                $this->table . '.total_biaya as total_biaya',
                $this->table . '.hutang_biaya as hutang_biaya',
                $this->table_member . '.no_member as no_member'
            )
            ->where($this->table . '.status_pembayaran', 'terbayar')
            ->Where($this->table . '.status', 3);

        if (!empty(session('cabang_session'))) {
            $data_->where($this->table . '.lokasi_id', '=', session('cabang_session'));
        }

        if (!empty(session('cabang_id'))) {
            $data_->where($this->table . '.lokasi_id', base64_decode(session('cabang_id')));
        }

        if (!empty($request->starts) && !empty($request->ends)) {
            $data_->whereBetween(
                DB::raw('DATE(transaksi.tanggal_bayar)'),
                [$request->starts, $request->ends]
            );
        }

        $data_->orderBy('id', 'DESC')
            ->groupBy('id')
            ->get();

        if (request()->ajax()) {

            return datatables()->of($data_)
                ->addColumn('agent', 'information.payment.content.data.agent')
                ->addColumn('action', 'information.payment.content.data.action_button')
                ->rawColumns(['action', 'agent'])
                ->addIndexColumn()
                ->make(true);
        }
    }
}