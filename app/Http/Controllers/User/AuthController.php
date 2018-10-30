<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Notification\SmsService;
use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['cellphone', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function otp(Request $request)
    {
        $cellphone = $request->input('cellphone');
        /** @var User $user */
        $user = User::where('cellphone', $cellphone)->first();
        // 1 means sing-in, 2 means sign-up
        $status = 1;
        if ($user === null) {
            $status = 2;
        }

        $code = rand(10000, 99999);
        /** @var SmsService $smsService */
        $smsService = app(SmsService::class);
        $smsService->send($cellphone, trans('auth.user.otp', ['code' => $code]));


        return response()->json([
            'status' => $status,
            'name' => $user->name ?? 'Loop'
        ]);
    }
}
