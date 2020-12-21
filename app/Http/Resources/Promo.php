<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Promo extends JsonResource
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
            'id' => $this->id,
            'gambar' => (empty($this->gambar) ? asset('/images/noimage.jpg') : asset('/storage/master-data/promo/uploads/' . $this->gambar)),
            'berlaku_dari' => $this->berlaku_dari,
            'berlaku_sampai' => $this->berlaku_sampai,
            'cabang' => empty($this->cabang) ? 'Semua Cabang' : $this->cabang,
            'deskripsi' => $this->deskripsi,
        ];
    }
}