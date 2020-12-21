<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class ReservationModel extends Model
{
    protected $table = 'transaksi';
    protected $fillable = [
        'member_id', 'no_transaksi', 'lokasi_id', 'jumlah_orang',
        'waktu_reservasi', 'dp', 'cara_bayar', 'bank_id',  'total_biaya', 'hutang_biaya'
    ];
}
