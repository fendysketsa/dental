<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Terapist extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'jabatan' => $this->jabatan,
            'foto' => (empty($this->foto) ? asset('/images/noimage.jpg') : asset('/storage/master-data/employee/uploads/' . $this->foto)),
            'available' => ($this->on_work == 'true' ? 'false' : $this->available),
        ];
    }
}