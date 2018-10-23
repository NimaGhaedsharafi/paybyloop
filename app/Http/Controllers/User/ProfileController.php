<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProfileController
 * @package App\Http\Controllers\User
 */
class ProfileController extends Controller
{
    /**
     * On Splash, client request and send information about build and os and receive some basic info
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function config(Request $request)
    {
        $this->validate($request, [
            'build' => 'required',
            'os' => 'required',
        ]);

        return response()->json([
            'supported_build' => 1,
            'latest_build' => 2,
            'update_url' => 'https://loop.com'
        ]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return response(Auth::user());
    }
}
