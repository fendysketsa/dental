<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Reservation extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'transaksi_id' => $this->id,
            'cara_bayar' => $this->cara_bayar,
            'member_id' => $this->member_id,
            'lokasi_id' => $this->lokasi_id,
            'lokasi' => $this->nama_lokasi,
            'jumlah_orang' => $this->jumlah_orang,
            'waktu_reservasi' => $this->waktu_reservasi,
            'dp' => $this->dp,
            'status' => $this->status,
            'status_aktifasi' => ($this->status == 2 ? true : false)
        ];
    }
}
