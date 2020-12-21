<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LayananCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (count($this->collection) == 0) {
            return [
                'code' => 500,
                'message' => 'Data tidak diketemukan',
                'data' => []
            ];
        }

        if ($request->is('api/layanan/show/*')) {
            return [
                'code' => 200,
                'message' => 'Berhasil ambil data layanan detail',
                'data' => $this->collection
            ];
        }

        return [
            'code' => 200,
            'message' => 'Berhasil ambil data layanan',
            'data' => $this->collection
        ];
    }
}
