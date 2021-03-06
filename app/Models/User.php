<?php

namespace App\Models;

use App\Models\Role;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use App\Notifications\ApiNotifAuth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'password', 'provider', 'provider_id', 'rec_pass'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function pegawaiBelongs()
    {
        $data = DB::table('pegawai')
            ->where('user_id', auth()->user()->id)->first();
        $data_ = !empty($data) ? $data->role : 1;
        $dataRole = array('?', 'Super Admin', 'Finance', 'Dokter', 'Kasir', 'Owner/Admin Pusat');

        $imagesMember = empty($data->foto) ? asset('/images/brokenimage.jpg') : (Storage::disk('profile')->exists($data->foto) ? asset('/storage/master-data/employee/uploads/' . $data->foto) : asset('/images/brokenimage.jpg'));

        return [
            'jabatan' => empty($data->jabatan) ? 'Administrator' : $data->jabatan,
            'role' => $dataRole[$data_],
            'img' => empty($data->foto) ? asset('/images/noimage.jpg') : $imagesMember,
        ];
    }
}
