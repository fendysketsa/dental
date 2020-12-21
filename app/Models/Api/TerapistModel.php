<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class TerapistModel extends Model
{
    protected $table = "pegawai";
    protected $fillable = [
        'id', 'nama', 'jabatan', 'foto'
    ];
}