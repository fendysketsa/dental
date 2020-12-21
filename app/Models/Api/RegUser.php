<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class RegUser extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'name', 'email', 'rec_pass',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
