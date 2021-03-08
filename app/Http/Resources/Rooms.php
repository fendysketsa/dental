<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Rooms extends JsonResource
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
            'images' => (empty($this->images) ? asset('/images/noimage.jpg') : $this->ArImage($this->images)),
            'name' => $this->id,
            'desription' => $this->description,
            'price' => $this->price,
        ];
    }

    private function ArImage($image)
    {
        $img = null;
        if (is_array(json_decode($image, true)) && !empty($image)) {
            $img = array();
            $newImage = json_decode($image, true);

            foreach ($newImage as $im) {
                array_push($img, asset('/storage/master-data/room/uploads/' . $im));
            }
        }

        return $img;
    }
}
