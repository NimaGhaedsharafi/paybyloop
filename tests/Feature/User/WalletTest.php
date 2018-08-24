<?php

namespace Tests\Feature\User;

use App\Services\Wallet\WalletService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\Feature\FeatureCase;

/**
 * Class WalletTest
 * @package Tests\Feature\User
 */
class WalletTest extends FeatureCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    /**
     * @test
     */
    public function get_list_wallet_transaction()
    {
        $this->impersonate();

        $wallet = new WalletService();
        for ($i = 0; $i < 5; $i++) {
            $wallet->creditor($this->user, rand(1, 10) * 1000, 1, "");
        }


        $this->json('GET', route('v1.user.wallet.list'))
            ->assertOk()
            ->assertJsonCount(5);
    }
}
