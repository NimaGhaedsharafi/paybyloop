<?php

namespace Tests\Feature\User;

use App\Enums\ErrorCode;
use App\Services\Notification\SmsService;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Cache;
use Prophecy\Argument;
use Tests\Feature\FeatureCase;

class AuthTest extends FeatureCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    /**
     * @test
     */
    public function otp_returns_log_in_data_once_user_is_registered()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $smsService = $this->prophesize(SmsService::class);
        $smsService->send($user->cellphone, Argument::any());
        app()->instance(SmsService::class, $smsService);

        $this->json('POST', route('v1.user.otp'), [
            'cellphone' => $user->cellphone,
        ])->assertOk()->assertJson([
            'status' => 1,
            'name' => $user->name
        ]);
    }

    /**
     * @test
     */
    public function otp_returns_sign_in_data_once_user_is_not_registered()
    {
        $smsService = $this->prophesize(SmsService::class);
        $cellphone = '+989025813222';
        $smsService->send($cellphone, Argument::any());
        app()->instance(SmsService::class, $smsService);

        $this->json('POST', route('v1.user.otp'), [
            'cellphone' => $cellphone,
        ])->assertOk()->assertJson([
            'status' => 2,
            'name' => 'Loop'
        ]);
    }

    /**
     * @test
     */
    public function registered_user_be_should_logged_in_once_enters_correct_pin()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $smsService = $this->prophesize(SmsService::class);
        $smsService->send($user->cellphone, Argument::any());
        app()->instance(SmsService::class, $smsService);

        $this->json('POST', route('v1.user.otp'), [
            'cellphone' => $user->cellphone,
        ])->assertOk();


        $code = Cache::get('otp:' . $user->cellphone);

        $this->json('POST', route('v1.user.otp.login'), [
            'cellphone' => $user->cellphone,
            'code' => $code,
        ])->assertOk()->json([
            'access_token',
            'token_type',
            'expires_in'
        ]);
    }

    /**
     * @test
     */
    public function registered_user_should_not_be_logged_in_once_enters_incorrect_pin()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $this->json('POST', route('v1.user.otp.login'), [
            'cellphone' => $user->cellphone,
            'code' => '12345',
        ])->assertForbidden()->json([
            'status' => ErrorCode::InvalidOTPToken,
            'message',
        ]);
    }

    /**
     * @test
     */
    public function login_needs_two_parameters()
    {
        $this->json('POST', route('v1.user.otp.login'), [
            'code' => '12345',
        ])->assertStatus(422);


        $this->json('POST', route('v1.user.otp.login'), [
            'cellphone' => '+989025813222',
        ])->assertStatus(422);

        $this->json('POST', route('v1.user.otp.login'), [
            'cellphone' => '+989025813222',
            'code' => '123451',
        ])->assertStatus(422);
    }

    /**
     * @test
     */
    public function new_user_can_register_with_otp_code()
    {
        $smsService = $this->prophesize(SmsService::class);
        $cellphone = '+989025813222';
        $smsService->send($cellphone, Argument::any());
        app()->instance(SmsService::class, $smsService);

        $this->json('POST', route('v1.user.otp'), [
            'cellphone' => $cellphone,
        ])->assertOk();


        $code = Cache::get('otp:' . $cellphone);

        $this->json('POST', route('v1.user.otp.register'), [
            'cellphone' => $cellphone,
            'first_name' => 'fname',
            'last_name' => 'lname',
            'email' => 'me@nifi.ir',
            'code' => $code,
        ])->assertOk()->json([
            'access_token',
            'token_type',
            'expires_in'
        ]);
    }
}
