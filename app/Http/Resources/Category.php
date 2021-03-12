<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
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
            'icon' => (empty($this->icon) ? asset('/images/noimage.jpg') : asset('/storage/master-data/category/uploads/icon/' . $this->icon)),
        ];
    }
}
