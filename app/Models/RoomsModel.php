<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomsModel extends Model
{
    protected $table = "room";
    protected $fillable = [
        'id', 'images', 'name', 'prices', 'description'
    ];
}
