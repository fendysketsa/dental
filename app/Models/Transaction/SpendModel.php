<?php

namespace App\Models\Transaction;

use DB;
use Illuminate\Database\Eloquent\Model;

class SpendModel extends Model
{
    public static function getAutoNoPengeluaran()
    {
        $no_pengeluaran = DB::table('pengeluaran')->orderBy('id', 'asc')->get()->last();
        if (empty($no_pengeluaran)) {
            $value = sprintf("%09d", 1);
        } else {
            $value = sprintf("%09d", (ltrim($no_pengeluaran->no_pengeluaran) + 1));
        }
        return $value;
    }
}
