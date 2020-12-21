<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukModel extends Model
{
    protected $table = "produk";
    protected $fillable = [
        'id', 'nama', 'gambar', 'harga_jual', 'harga_jual_member'
    ];
}
