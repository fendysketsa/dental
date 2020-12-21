<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'role_id', 'user_id'
    ];
    /*
    	* Method untuk yang mendefinisikan relasi antara model user dan model Role
    	*/
    public function getUserObject()
    {
        return $this->belongsToMany(User::class)->using(UserRole::class);
    }
}
