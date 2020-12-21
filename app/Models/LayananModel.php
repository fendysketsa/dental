<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LayananModel extends Model
{
    protected $table = "layanan";
    protected $fillable = [
        'id', 'nama', 'deskripsi', 'gambar', 'harga'
    ];
}
