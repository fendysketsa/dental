<?php

namespace App\Api;

use Illuminate\Database\Eloquent\Model;

class ReservationDetailModel extends Model
{
    protected $table = 'transaksi_detail';
    protected $fillable = [
        'layanan_id', 'pegawai_id', 'harga', 'kuantitas'
    ];
}
