<?php

namespace App\Http\Controllers\User;

use App\Enums\ErrorCode;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Services\Notification\SmsService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
        $ttl = config('auth.otp.ttl');
        if ($user === null) {
            $status = 2;
            $ttl = config('auth.otp.ttl_register');
        }

        // let's return the code instead of regeneration
        $code = Cache::get('otp:' . $cellphone);
        if ($code === null) {
            $code = rand(10000, 99999);
            Cache::put('otp:' . $cellphone, $code, $ttl);
        }
        /** @var SmsService $smsService */
        $smsService = app(SmsService::class);
        $smsService->send($cellphone, trans('auth.user.otp', ['code' => $code]));


        return response()->json([
            'status' => $status,
            'name' => $user->name ?? 'Loop'
        ]);
    }

    public function otpLogin(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|size:5',
            'cellphone' => 'required'
        ]);

        $cellphone = $request->input('cellphone');
        $code = Cache::get('otp:' . $cellphone, '');

        if ($code != $request->input('code')) {
            throw new ApiException(ErrorCode::InvalidOTPToken, 'Code is expired or invalid', 403);
        }
        /** @var User $user */
        $user = User::where('cellphone', $cellphone)->first();

        return $this->respondWithToken(auth()->login($user));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function otpRegister(Request $request)
    {
        /** @var User $user */
       $user = new User();
       $user->first_name = $request->input('first_name');
       $user->last_name = $request->input('last_name');
       $user->email = $request->input('email', '');
       $user->cellphone = $request->input('cellphone');
       $user->cellphone_verified = true;
       $user->save();

        return $this->respondWithToken(auth()->login($user));
    }
}
