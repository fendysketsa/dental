<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class News extends JsonResource
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
            'judul' => $this->judul,
            'gambar' => (empty($this->gambar) ? asset('/images/noimage.jpg') : asset('/storage/master-data/berita/uploads/' . $this->gambar)),
            'deskripsi' => $this->deskripsi,
        ];
    }
}