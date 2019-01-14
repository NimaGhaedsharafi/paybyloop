<?php

namespace App\Http\Controllers\User;

use App\Enums\ErrorCode;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Jobs\AsyncSMS;
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

        if ($user === null) {
            $name = 'Loop';
            $status = 2;
            $ttl = config('auth.otp.ttl_register');
        } else {
            // 1 means sing-in, 2 means sign-up
            $status = 1;
            $ttl = config('auth.otp.ttl');
            $name = $user->getName();
        }

        // let's return the code instead of regeneration
        $code = Cache::get('otp:' . $cellphone);
        if ($code === null) {
            $code = (app()->environment('production')) ? rand(10000, 99999) : 12345;
            Cache::put('otp:' . $cellphone, $code, $ttl);
        }

        $this->dispatch(new AsyncSMS($cellphone, trans('sms.otp', ['code' => $code], 'fa')));


        return response()->json([
            'status' => $status,
            'name' => $name
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

        Cache::forget('otp:' . $request->input('cellphone'));
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
        $this->validate($request, [
            'code' => 'required|size:5',
            'cellphone' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email',
        ]);

        $code = Cache::get('otp:' . $request->input('cellphone'), '');

        if ($code != $request->input('code')) {
            throw new ApiException(ErrorCode::InvalidOTPToken, 'Code is expired or invalid', 403);
        }

       Cache::forget('otp:' . $request->input('cellphone'));

        /** @var User $user */
       $user = new User();
       $user->first_name = $request->input('first_name');
       $user->last_name = $request->input('last_name');
       $user->email = $request->input('email', '');
       $user->cellphone = $request->input('cellphone');
       $user->cellphone_verified = true;
       $user->password = rand(10000, 99999);
       $user->email_verified = 0;
       $user->save();

        return $this->respondWithToken(auth()->login($user));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateOtp(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|size:5',
            'cellphone' => 'required',
        ]);

        $code = Cache::get('otp:' . $request->input('cellphone'), '');

        if ($code != $request->input('code')) {
            throw new ApiException(ErrorCode::InvalidOTPToken, 'Code is expired or invalid', 403);
        }

        return response()->json(['status' => 'ok']);
    }
}
