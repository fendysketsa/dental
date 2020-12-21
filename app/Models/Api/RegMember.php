<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use DB;

class RegMember extends Model
{
    protected $table = "member";
    protected $fillable = [
        'nama', 'jenis_kelamin', 'no_member', 'email'
    ];

    public function getAutoNoMember()
    {
        $no_member = DB::table($this->table)->orderBy('id', 'asc')->get()->last();
        if (empty($no_member)) {
            $value = sprintf("%09d", 1);
        } else {
            $value = sprintf("%09d", (str_replace('GW-', '', ltrim($no_member->no_member)) + 1));
        }
        return 'GW-' . $value;
    }
}
