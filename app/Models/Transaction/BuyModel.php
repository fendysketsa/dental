<?php

namespace App\Models\Transaction;

use DB;
use Illuminate\Database\Eloquent\Model;

class BuyModel extends Model
{
    public static function getAutoNoPembelian()
    {
        $no_pembelian = DB::table('pembelian')->orderBy('id', 'asc')->get()->last();
        if (empty($no_pembelian)) {
            $value = sprintf("%09d", 1);
        } else {
            $value = sprintf("%09d", (ltrim($no_pembelian->no_pembelian) + 1));
        }
        return $value;
    }
}
