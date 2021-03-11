<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Medinas extends JsonResource
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
            'images' => (empty($this->gambar) ? asset('/images/noimage.jpg') : asset('/storage/master-data/home-page/uploads/' . $this->gambar)),
            'icon' => (empty($this->icon) ? asset('/images/noimage.jpg') : asset('/storage/master-data/home-page/uploads/icon/' . $this->icon)),
            'video' => $this->video,
            'name' => $this->judul,
            'description' => $this->deskripsi,
        ];
    }
}
