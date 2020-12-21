<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class BankModel extends Model
{
    protected $table = 'bank';
    protected $fillable = [
        'id', 'nama', 'kode', 'pemilik'
    ];
}
