<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedinasModel extends Model
{
    protected $table = "home_page";
    protected $fillable = [
        'id', 'gambar', 'icon', 'video', 'judul', 'deskripsi'
    ];
}
