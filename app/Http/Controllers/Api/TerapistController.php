<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Api\TerapistModel;
use App\Http\Resources\TerapistCollection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class TerapistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cabang = Input::get('cabang');
        $tanggal = Input::get('tanggal');
        $jam = Input::get('jam');
        $layanan = Input::get('layanan');

        if (!empty($cabang) && !empty($tanggal) && !empty($jam) && !empty($layanan)) {
            return new TerapistCollection(TerapistModel::leftJoin('kalendar_shift', 'kalendar_shift.pegawai_id', '=', 'pegawai.id')
                ->leftJoin('shift', 'shift.id', '=', 'kalendar_shift.shift_id')
                ->leftJoin('kualifikasi_terapis', 'kualifikasi_terapis.pegawai_id', '=', 'kalendar_shift.pegawai_id')
                ->leftJoin('transaksi_detail', 'transaksi_detail.pegawai_id', '=', 'kalendar_shift.pegawai_id')
                ->leftJoin('transaksi', 'transaksi_detail.transaksi_id', '=', 'transaksi.id')
                ->where('pegawai.role', 3)
                ->where('kalendar_shift.ijin', '=', 0)
                ->where('kualifikasi_terapis.layanan_id', $layanan)
                // ->orWhere('pegawai.cabang_id', $cabang)
                ->where('kalendar_shift.cabang_id', $cabang)
                ->where('kalendar_shift.tanggal', $tanggal)
                //->where(DB::RAW('DATE(transaksi_detail.created_at)'), '>=', date('Y-m-d'))
                ->WhereTime('shift.jam_akhir', '>', $jam)
                ->select(
                    DB::raw("IF((SELECT COUNT(dtp.pegawai_id) from transaksi_detail dtp left join transaksi t ON t.id = dtp.transaksi_id where t.status_pembayaran != 'terbayar' AND t.status != 4 AND t.status != 1 AND dtp.pegawai_id = pegawai.id AND DATE(t.waktu_reservasi) = '" . $tanggal . "' AND TIME(t.waktu_reservasi) <= '" . $jam . "') > 0, 'true', 'false') as on_work"),
                    DB::raw("IF(shift.jam_awal <= '$jam' AND shift.jam_akhir > '$jam', 'true', 'false') as available"),
                    'pegawai.*'
                )
                ->groupBy('kalendar_shift.pegawai_id')
                ->get());
        }

        return new TerapistCollection(TerapistModel::leftJoin('kalendar_shift', 'kalendar_shift.pegawai_id', '=', 'pegawai.id')
            ->leftJoin('shift', 'shift.id', '=', 'kalendar_shift.shift_id')
            ->where('pegawai.role', 3)
            ->where('kalendar_shift.ijin', '=', 0)
            ->where('kalendar_shift.tanggal', date('Y-m-d'))
            ->whereTime('shift.jam_akhir', '>=', date('H'))
            ->select('pegawai.*')
            ->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
