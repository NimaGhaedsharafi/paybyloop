<?php

namespace Tests\Feature\User;

use App\Services\Gift\GiftService;
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
        /** @var GiftService $service */
        $service = app(GiftService::class);
        $voucher = $service->create(1000, 'random voucher', Carbon::tomorrow(), 'code', 1);

        $this->json('POST', route('v1.user.promotion.gift'), ['code' => $voucher])->assertOk();
    }
}
