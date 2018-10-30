<?php

namespace Tests\Feature\User;

use App\Services\Notification\SmsService;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
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
}
