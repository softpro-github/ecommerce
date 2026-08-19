<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function orders()
    {
        $orders = Auth::user()->orders()->latest()->get();

        return view('account.orders', compact('orders'));
    }
}
