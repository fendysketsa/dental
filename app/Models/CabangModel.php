<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CabangModel extends Model
{
    protected $table = "cabang";
    protected $fillable = [
        'id', 'kode', 'nama'
    ];
}
