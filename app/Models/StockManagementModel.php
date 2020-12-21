<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockManagementModel extends Model
{
    protected $table = 'produk';
    protected $fillable = [
        'stok'
    ];
}
