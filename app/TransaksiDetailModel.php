<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetailModel extends Model
{
    protected $table = "transaksi_detail";
    protected $fillable = [
        'transaksi_id', 'produk_id', 'kuantitas', 'member_id'
    ];
}
