<?php

namespace App\Models\Transaction;

use DB;
use Illuminate\Database\Eloquent\Model;

class TransaksiModel extends Model
{
    public static function getAutoNoTransaksi()
    {
        $no_transaksi = DB::table('transaksi')->orderBy('id', 'asc')->get()->last();
        if (empty($no_transaksi)) {
            $value = sprintf("%09d", 1);
        } else {
            $value = sprintf("%09d", (ltrim($no_transaksi->no_transaksi) + 1));
        }
        return $value;
    }
}
