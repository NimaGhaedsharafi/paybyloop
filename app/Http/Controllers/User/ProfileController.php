<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\User;
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

        /** @var User $user */
        $user = auth()->user();

        if ($user !== null && $user->isBlocked()) {
            return response()->json([
                'message' => trans('system.blocked', [], 'fa')
            ], 403);
        }

        $os = $request->input('os');

        return response()->json([
            'supported_build' => config("loop.version.{$os}.supported"),
            'latest_build' => config("loop.version.{$os}.latest"),
            'update_url' => config("loop.version.{$os}.url")
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
