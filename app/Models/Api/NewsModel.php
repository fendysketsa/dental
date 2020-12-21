<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class NewsModel extends Model
{
    protected $table = 'berita';
    protected $fillable = [
        'id', 'judul', 'gambar'
    ];
}
