<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MemberModel extends Model
{
    protected $table = 'member';
    protected $fillable = [
        'id', 'user_id', 'no_member', 'foto', 'nama', 'email', 'telepon', 'saldo', 'status'
    ];

    public static function getAutoNoMember()
    {
        $no_member = DB::table('member')->orderBy('id', 'asc')->get()->last();
        if (empty($no_member)) {
            $value = sprintf("%09d", 1);
        } else {
            $value = sprintf("%09d", (str_replace('GW-', '', ltrim($no_member->no_member)) + 1));
        }
        return 'GW-' . $value;
    }
}
