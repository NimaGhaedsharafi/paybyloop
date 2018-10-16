<?php

namespace Tests\Feature\User;

use App\Services\Voucher\VoucherService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\Feature\FeatureCase;

class PromotionTest extends FeatureCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @test
     */
    public function redeem_voucher()
    {
        $this->impersonate();
        /** @var VoucherService $service */
        $service = app(VoucherService::class);
        $voucher = $service->create(1000, 'random voucher', Carbon::tomorrow(), 'code', 1);

        $this->json('POST', 'promotion/redeem', ['code' => $voucher])->assertOk();
    }
}
