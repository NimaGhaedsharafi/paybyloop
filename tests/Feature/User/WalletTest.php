<?php

namespace Tests\Feature\User;

use App\User;
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
        $user = factory(User::class)->create();
        Auth::login($user);
        
        $this->json('GET', route('v1.user.wallet.list'))
            ->assertOk()
            ->assertJson([]);
    }
}
