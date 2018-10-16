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

    /**
     * @test
     */
    public function redeem_voucher()
    {
        $code = $this->createVoucher(1000);
        $userId = 1;

        $this->expectsEvents(VoucherRedeemed::class);
        /** @var VoucherService $voucherService */
        $voucherService = new VoucherService();
        $voucherService->redeem($userId, $code);

        $this->assertDatabaseHas('voucher_log', [
            'user_id' => $userId,
            'voucher' => $code,
            'applied_at' => Carbon::now()
        ]);
    }

    /**
     * @param integer|null $amount
     * @param string|null $title
     * @param Carbon|null $expiresIn
     * @param string|null $code
     * @param integer|null $maxUseTime
     * @return string
     */
    private function createVoucher($amount = null, $title = null, $expiresIn = null, $code = null, $maxUseTime = null)
    {
        // fixture
        $amount = $amount ?? rand(1, 10) * 1000;
        $title = $title ?? 'my campaign';
        $expiresIn = $expiresIn ?? Carbon::now()->addDay();
        $code = $code ?? str_random(5);
        $maxUseTime = $maxUseTime ?? rand(1, 10);

        /** @var VoucherService $voucher */
        $voucher = new VoucherService();
        return $voucher->create($amount, $title, $expiresIn, $code, $maxUseTime);
    }
}
