<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketModel extends Model
{
    protected $table = "paket";
    protected $fillable = [
        'id', 'nama', 'keterangan', 'gambar'
    ];
}
