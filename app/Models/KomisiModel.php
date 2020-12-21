<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomisiModel extends Model
{
    protected $table = 'pegawai';
    protected $hidden = [
        'foto', 'role', 'created_at', 'updated_at', 'status', 'komisi', 'id'
    ];
}