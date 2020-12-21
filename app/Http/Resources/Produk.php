<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Produk extends JsonResource
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
            'nama' => $this->nama,
            'keterangan' => $this->keterangan,
            'gambar' => (empty($this->gambar) ? asset('/images/noimage.jpg') : asset('/storage/master-data/product/uploads/' . $this->gambar)),
            'harga_jual' => $this->harga_jual,
            'harga_jual_member' => $this->harga_jual_member
        ];
    }
}