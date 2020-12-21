<?php

namespace App\Exports;

use App\Models\KomisiModel;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class KomisiExport implements FromView
{
    public function view(): View
    {
        $data = KomisiModel::leftJoin(
            'cabang',
            'cabang.id',
            '=',
            'pegawai.cabang_id'
        )->select(
            'pegawai.id',
            'cabang.nama as cabang',
            'pegawai.nama',
            'pegawai.jabatan',
            DB::raw("CONCAT(komisi, '%') as upah"),
            DB::raw("(SELECT IF(harga, SUM(harga*komisi/100), 0) FROM transaksi_detail join transaksi ON transaksi.id = transaksi_detail.transaksi_id where transaksi.status_pembayaran = 'terbayar' AND transaksi_detail.pegawai_id = pegawai.id "
                . (!empty($_GET) ?
                    ' AND ' . " DATE(transaksi.created_at) BETWEEN '" . DATE("Y-m-d", strtotime($_GET['starts'])) . "' AND '" . DATE("Y-m-d", strtotime($_GET['ends'])) . "'" : '') . ") AS total_komisi"),
            DB::raw("(SELECT IF(layanan_id, COUNT(layanan_id), 0) FROM transaksi_detail join transaksi ON transaksi.id = transaksi_detail.transaksi_id where transaksi.status_pembayaran = 'terbayar' AND transaksi_detail.pegawai_id = pegawai.id "
                . (!empty($_GET) ?
                    ' AND ' . "DATE(transaksi.created_at) BETWEEN '" . DATE("Y-m-d", strtotime($_GET['starts'])) . "' AND '" . DATE("Y-m-d", strtotime($_GET['ends'])) . "'" : '') . ") AS total_layanan")
        )->where('role', 3);

        if (!empty(session('cabang_session')) || !empty(base64_decode(session('cabang_id')))) {
            $data->where('pegawai.cabang_id', (session('cabang_session') ? session('cabang_session') : base64_decode(session('cabang_id'))));
        }

        return view('monitoring.employee.fee.content.export', [
            'session_cabang' => getNamaCabang(session('cabang_id') ? session('cabang_id') : session('cabang_session')),
            'periode' => $_GET,
            'export_komisi' => $data->get()
        ]);
    }
}
