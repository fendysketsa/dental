<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProdukCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($request->is('api/produk/show/*')) {
            return [
                'code' => 200,
                'message' => 'Berhasil ambil data produk detail',
                'data' => $this->collection
            ];
        }

        return [
            'code' => 200,
            'message' => 'Berhasil ambil data produk',
            'data' => $this->collection
        ];
    }
}
