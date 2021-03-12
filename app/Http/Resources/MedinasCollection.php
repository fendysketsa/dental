<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MedinasCollection extends ResourceCollection
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
            'code' => 200,
            'message' => 'Berhasil ambil data about medina',
            'data' => count($this->collection) < 2 ? $this->collection[0] : $this->collection,
        ];
    }
}
