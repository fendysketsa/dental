<?php

namespace App\Models\Api\Member;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = "member";
    protected $fillable = [
        'nama', 'telepon', 'email', 'jenis_kelamain', 'alamat', 'no_member'
    ];
}
