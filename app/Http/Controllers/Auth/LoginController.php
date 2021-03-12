<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    public function __construct()
    {
        $this->cekOrder();
        $this->cekOldOrder();
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request)
    {

        if ($request->user()->hasRole('super-admin')) {
            return redirect('/home');
        }

        if ($request->user()->hasRole('manager')) {
            return redirect('/home');
        }

        if ($request->user()->hasRole('kasir')) {
            getBranch();
            return redirect('/registrations');
        }

        if ($request->user()->hasRole('owner')) {
            getBranch('owner');
            return redirect('/home');
        }
    }

    protected function cekOrder()
    {
        $cekPrint = DB::table('transaksi')
            ->where('status', 2)
            ->where('status_pembayaran', 'pendaftaran')
            ->where(DB::RAW('DATE(waktu_reservasi)'), '=', DATE('Y-m-d'))
            ->where(DB::RAW('date_add(TIME(waktu_reservasi),interval 360 minute)'), '<', DATE('H:i'));

        if (!empty($cekPrint->get())) {
            foreach ($cekPrint->get() as $r) {
                if ($r->print_act == 0 or empty($r->print_act)) {
                    DB::table('transaksi')
                        ->where('status', 2)
                        ->where('status_pembayaran', 'pendaftaran')
                        ->where(DB::RAW('DATE(waktu_reservasi)'), '=', DATE('Y-m-d'))
                        ->where(DB::RAW('date_add(TIME(waktu_reservasi),interval 360 minute)'), '<', DATE('H:i'))->where('id', $r->id)
                        ->update([
                            'status' => 4
                        ]);
                }
            }
        }
    }

    protected function cekOldOrder()
    {
        DB::table('transaksi')
            ->where('status', 2)
            ->where('status_pembayaran', 'pendaftaran')
            ->where(DB::RAW('DATE(waktu_reservasi)'), '<', DATE('Y-m-d'))
            ->update([
                'status' => 4
            ]);
    }
}
