<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReservationCollection extends ResourceCollection
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
            'message' => 'Berhasil '
                . (!empty($request->action) && $request->action == 'cancel' ? 'membatalkan dan ' : '')
                . 'mengambil data history member',
            'data' => $this->collection
        ];
    }
}