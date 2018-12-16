<?php

namespace Tests\Unit;

use App\Services\Gift\Events\GiftUsed;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Services\Gift\GiftService;

/**
 * Class GiftTest
 * @package Tests\Unit
 */
class GiftTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * @test
     */
    public function create_gift()
    {
        // fixture
        $amount =  rand(1, 10) * 1000;
        $title = 'my campaign';
        $expiresIn = Carbon::now()->addDay();
        $code = str_random(5);
        $maxUseTime = rand(1, 10);

        /** @var GiftService $gift */
        $gift = new GiftService();
        $giftCode = $gift->create($amount, $title, $expiresIn, $code, $maxUseTime);

        $this->assertSame($giftCode, $code);
        $this->assertDatabaseHas('gifts', [
            'amount' => $amount,
            'title' => $title,
            'expires_in' => $expiresIn,
            'code' => $code,
            'max_use_time' => $maxUseTime
        ]);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function redeem_gift()
    {
        $code = $this->createGift(1000);
        $userId = 1;

        $this->expectsEvents(GiftUsed::class);
        /** @var GiftService $giftService */
        $giftService = new GiftService();
        $result = $giftService->redeem($userId, $code);

        $this->assertNotNull($result);
        $this->assertDatabaseHas('gift_logs', [
            'user_id' => $userId,
            'code' => $code,
            'applied_at' => Carbon::now()
        ]);
    }

    /**
     * @test
     */
    public function redeem_an_invalid_voucher_should_return_false()
    {
        $code = 'invalid';
        $userId = 1;
        /** @var GiftService $giftService */
        $giftService = new GiftService();
        $result = $giftService->redeem($userId, $code);

        $this->assertNull($result);
        $this->assertDatabaseHas('gift_logs', [
            'user_id' => $userId,
            'code' => $code,
            'applied_at' => null
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
    private function createGift($amount = null, $title = null, $expiresIn = null, $code = null, $maxUseTime = null)
    {
        // fixture
        $amount = $amount ?? rand(1, 10) * 1000;
        $title = $title ?? 'my campaign';
        $expiresIn = $expiresIn ?? Carbon::now()->addDay();
        $code = $code ?? str_random(5);
        $maxUseTime = $maxUseTime ?? rand(1, 10);

        /** @var GiftService $gift */
        $gift = new GiftService();
        return $gift->create($amount, $title, $expiresIn, $code, $maxUseTime);
    }
}
