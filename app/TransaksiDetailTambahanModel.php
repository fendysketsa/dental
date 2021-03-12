<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetailTambahanModel extends Model
{
    protected $table = "transaksi_tambahan";
    protected $fillable = [
        'transaksi_id', 'name', 'price'
    ];
}
