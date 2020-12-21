<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingNotaModel extends Model
{
    protected $table = 'setting_nota';
    protected $fillable = [
        'title', 'contact_info', 'salutation'
    ];
}
