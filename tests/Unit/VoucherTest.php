<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;

class VoucherTest extends TestCase
{
    /**
     * @test
     */
    public function create_voucher()
    {
        // fixture
        $amount =  rand(1, 10) * 1000;
        $title = 'my campaign';
        $expiresIn = Carbon::now()->addDay();
        $code = null;
        $maxUseTime = rand(1, 10);

        /** @var VoucherService $voucher */
        $voucher = new VoucherService();

        $voucher->create($amount, $title, $expiresIn, $code, $maxUseTime);

        $this->assertDatabaseHas('vouchers', [
            'amount' => $amount,
            'title' => $title,
            'expires_in' => $expiresIn,
            'code' => $code,
            'maxUseTime' => $maxUseTime
        ]);
    }
}
