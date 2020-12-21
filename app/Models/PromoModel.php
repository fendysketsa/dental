<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoModel extends Model
{
    protected $table = 'promo';
    protected $fillable = [
        'id', 'gambar', 'berlaku_dari', 'berlaku_sampai', 'cabang', 'deskripsi'
    ];
}