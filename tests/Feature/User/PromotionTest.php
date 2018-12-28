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

    /**
     * A basic test example.
     *
     * @test
     */
    public function redeem_an_invalid_voucher_fails()
    {
        $this->impersonate();
        $this->json('POST', route('v1.user.promotion.gift'), ['code' => 'gibberish'])->assertStatus(400)->assertJson([
            'message' => trans('voucher.gift.invalid', [], 'fa'), 'code' => 1004
        ]);
    }
}
