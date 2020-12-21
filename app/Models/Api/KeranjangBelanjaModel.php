<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class KeranjangBelanjaModel extends Model
{
    protected $table = 'keranjang_belanja';
    protected $fillable = [
        'member_id', 'produk_id'
    ];
}
