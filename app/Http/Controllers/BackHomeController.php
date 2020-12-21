<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackHomeController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            if (auth()->user()->hasRole('super-admin')) {
                return redirect('/home');
            }

            if (auth()->user()->hasRole('manager')) {
                return redirect('/home');
            }

            if (auth()->user()->hasRole('kasir')) {
                return redirect('/registrations');
            }

            if (auth()->user()->hasRole('owner')) {
                return redirect('/home');
            }
        }
        return redirect('login');
    }
}