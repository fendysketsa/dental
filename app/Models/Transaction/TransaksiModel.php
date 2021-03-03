<?php

namespace App\Models\Transaction;

use DB;
use Illuminate\Database\Eloquent\Model;

class TransaksiModel extends Model
{
    protected $table = 'transaksi';

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

    public static function getCodeUniqTransaksi($length)
    {
        $random = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $length);

        $cekRandom = DB::table('transaksi')->where('uniq_transaksi', $random)->orderBy('id', 'asc')->get()->last();

        if (empty($cekRandom)) {
            return $random;
        } else {
            return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $length);
        }
    }
}
