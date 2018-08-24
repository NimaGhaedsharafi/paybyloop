<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

/**
 * Class WalletTest
 * @package Tests\Feature\User
 */
class WalletTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    public function get_list_wallet_transaction()
    {
        $this->json('GET', route('v1.user.wallet.list'))
            ->assertOk()
            ->assertJson([]);
    }
}
