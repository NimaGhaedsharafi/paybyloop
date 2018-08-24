<?php

namespace App\Http\Controllers\User;

use App\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        return Wallet::where('user_id', Auth::user()->getAuthIdentifier())->where('user_type', 1)->get();
    }
}
