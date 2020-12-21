<?php

namespace App\Exports;

use App\SalesModel;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class SalesExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    // public function collection()
    // {
    //     //
    // }

    public function view(): View
    {
        $data_ = SalesModel::where('transaksi.status', '>', 0)
            ->leftJoin(
                'transaksi_detail',
                'transaksi_detail.transaksi_id',
                '=',
                'transaksi.id'
            )->leftJoin(
                'member',
                'member.id',
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
            ->where('transaksi.status', 3)
            ->where('transaksi.lokasi_id', (session('cabang_session') ? session('cabang_session') : base64_decode(session('cabang_id'))))
            ->whereBetween(DB::raw('DATE(transaksi.tanggal_bayar)'), ((!empty($_GET['starts']) && !empty($_GET['ends'])) ?
                [DATE("Y-m-d", strtotime($_GET['starts'])), DATE("Y-m-d", strtotime($_GET['ends']))] : '?'))
            ->groupBy('id')
            ->orderBy('id', 'DESC')
            ->get();

        return view('monitoring.penjualan.content.export', [
            'session_cabang' => getNamaCabang(session('cabang_id') ? session('cabang_id') : session('cabang_session')),
            'periode' => $_GET,
            'export_sales' => $data_
        ]);
    }
}