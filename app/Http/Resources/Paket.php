<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class Paket extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $dataOn = DB::table('paket')
            ->leftJoin('paket_detail', 'paket.id', '=', 'paket_detail.paket_id')
            ->leftJoin('layanan', 'layanan.id', '=', 'paket_detail.layanan_id')
            ->where('paket_detail.paket_id', $this->id)
            ->select(
                DB::raw('paket.harga as harga_paket'),
                DB::raw('SUM(layanan.waktu_pengerjaan) as time'),
                DB::raw('SUM(layanan.garansi) as garansi')
            )
            ->get();

        $dataBrand = DB::table('brand')
            ->leftJoin('layanan_detail', 'layanan_detail.brand_id', '=', 'brand.id')
            ->leftJoin('paket_detail', 'paket_detail.layanan_id', '=', 'layanan_detail.layanan_id')
            ->leftJoin('layanan', 'layanan.id', '=', 'paket_detail.layanan_id')
            ->leftJoin('paket', 'paket.id', '=', 'paket_detail.paket_id')
            ->where('paket_detail.paket_id', $this->id)
            ->select('brand.id', 'brand.nama', DB::raw("IF(brand.gambar, CONCAT('" . asset('/storage/master-data/brand/uploads') . "', '/', brand.gambar" . "), '" . asset('/images/noimage.jpg') . "') as gambar"))
            ->groupBy('brand.id')
            ->get();

        $dataOtherPaket = DB::table('paket')
            ->select('id', 'nama', 'harga', 'keterangan', DB::raw("IF(gambar, CONCAT('" . asset('/storage/master-data/paket/uploads') . "', '/', gambar" . "), '" . asset('/images/noimage.jpg') . "') as gambar"))
            ->whereNotIn('id', [$this->id])->get();

        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'keterangan' => $this->keterangan,
            'gambar' => (empty($this->gambar) ? asset('/images/noimage.jpg') : asset('/storage/master-data/package/uploads/' . $this->gambar)),
            'harga' => $dataOn[0]->harga_paket,
            'time' => $dataOn[0]->time,
            'garansi' => $dataOn[0]->garansi,
            'brand' => $dataBrand,
            'paket_lain' => $dataOtherPaket
        ];
    }
}