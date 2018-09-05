<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Services\Voucher\VoucherService;

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
        $code = str_random(5);
        $maxUseTime = rand(1, 10);

        /** @var VoucherService $voucher */
        $voucher = new VoucherService();
        $voucherCode = $voucher->create($amount, $title, $expiresIn, $code, $maxUseTime);

        $this->assertSame($voucherCode, $code);
        $this->assertDatabaseHas('vouchers', [
            'amount' => $amount,
            'title' => $title,
            'expires_in' => $expiresIn,
            'code' => $code,
            'max_use_time' => $maxUseTime
        ]);
    }
}
