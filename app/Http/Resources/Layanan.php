<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class Layanan extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $brand = array();
        $Dbrand = DB::table('layanan_detail')
            ->leftJoin('brand', 'brand.id', '=', 'layanan_detail.brand_id')
            ->select('brand.id', 'brand.gambar', 'brand.nama')
            ->where('layanan_id', $this->id)
            ->get();

        foreach ($Dbrand as $brd) {
            $brand[] = [
                'id' => $brd->id,
                'nama' => $brd->nama,
                'gambar' => (empty($brd->gambar) ? asset('/images/noimage.jpg') : asset('/storage/master-data/brand/uploads/' . $this->gambar)),
            ];
        }

        // $terapist = array();
        // $Dterapist = DB::table('kualifikasi_terapis')
        //     ->leftJoin('pegawai', 'pegawai.id', '=', 'kualifikasi_terapis.pegawai_id')
        //     ->leftJoin('kalendar_shift', 'pegawai.id', '=', 'kalendar_shift.pegawai_id')
        //     ->leftJoin('shift', 'shift.id', '=', 'kalendar_shift.shift_id')
        //     ->select('pegawai.id', 'pegawai.foto', 'pegawai.nama', 'pegawai.jabatan')
        //     ->where('layanan_id', $this->id)
        //     ->where('kalendar_shift.tanggal', date('Y-m-d'))
        //     ->where('shift.jam_akhir', '>', date('H'))
        //     ->get();

        // foreach ($Dterapist as $trp) {
        //     $terapist[] = [
        //         'id' => $trp->id,
        //         'nama' => $trp->nama,
        //         'foto' => (empty($trp->foto) ? asset('/images/noimage.jpg') : asset('/storage/master-data/employee/uploads/' . $this->foto)),
        //         'jabatan' => $trp->jabatan,
        //     ];
        // }

        $oth_serv = array();
        $rev_serv = array();
        $DOServ = DB::table('layanan')
            ->select('layanan.id', 'layanan.gambar', 'layanan.nama', 'layanan.harga')
            ->where('kategori_id', $this->kategori_id)
            ->whereNotIn('id', [$this->id])
            ->get();

        foreach ($DOServ as $oth) {
            $oth_serv[] = [
                'id' => $oth->id,
                'nama' => $oth->nama,
                'gambar' => (empty($oth->gambar) ? asset('/images/noimage.jpg') : asset('/storage/master-data/services/uploads/' . $this->gambar)),
                'harga' => $oth->harga,
                'link' => url('api/layanan/show', $oth->id)
            ];
        }

        $DRevServ = DB::table('layanan_review')
            ->leftJoin(
                'member',
                'member.id',
                '=',
                'layanan_review.member_id'
            )
            ->where('layanan_id', $this->id)
            ->select('member.nama', 'member.foto', 'layanan_review.*')
            ->get()->toArray();

        foreach ($DRevServ as $revser) {
            $rev_serv[] = [
                'id' => $revser->id,
                'tanggal' => $revser->created_at,
                'nama' => $revser->nama,
                'foto' => (empty($revser->foto) ? asset('/images/noimage.jpg') : asset('/storage/master-data/member/uploads/' . $this->foto)),
                'star' => $revser->star,
                'keterangan' => ($revser->keterangan ? $revser->keterangan : '-')
            ];
        }

        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'gambar' => (empty($this->gambar) ? asset('/images/noimage.jpg') : asset('/storage/master-data/services/uploads/' . $this->gambar)),
            'deskripsi' => $this->deskripsi,
            'harga' => $this->harga,
            'waktu_pengerjaan' => $this->waktu_pengerjaan,
            'review' => $rev_serv,
            'brand' => $brand,
            //'terapist' => $terapist,
            'other_services' => $oth_serv
        ];
    }
}