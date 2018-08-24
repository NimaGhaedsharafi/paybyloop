<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProfileController
 * @package App\Http\Controllers\User
 */
class ProfileController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return response(Auth::user());
    }
}