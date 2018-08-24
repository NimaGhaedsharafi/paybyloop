<?php

namespace Tests\Feature\User;

use App\Services\Wallet\WalletService;
use App\User;
use App\Wallet;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * Class WalletTest
 * @package Tests\Feature\User
 */
class WalletTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    /**
     * @test
     */
    public function get_list_wallet_transaction()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        Auth::login($user);

        $wallet = new WalletService();
        for ($i = 0; $i < 5; $i++) {
            $wallet->creditor($user, rand(1, 10) * 1000, 1, "");
        }


        $this->json('GET', route('v1.user.wallet.list'))
            ->assertOk()
            ->assertJsonCount(5);
    }
}
