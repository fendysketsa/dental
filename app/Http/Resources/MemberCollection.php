<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MemberCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($request->is('api/member/*')) {
            return [
                'code' => 200,
                'message' => 'Berhasil ambil data member',
                'data' => $this->collection[0]
            ];
        }

        return [
            'code' => 200,
            'message' => 'Berhasil ambil data member',
            'data' => $this->collection
        ];
    }
}
