<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 8/24/18
 * Time: 9:04 PM
 */

namespace Tests\Unit\Wallet;

use App\Services\Wallet\WalletService;
use App\User;
use App\Wallet;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class WalletServiceTest
 * @package Tests\Unit\Wallet
 */
class WalletServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function testBalance()
    {
        $user = factory(User::class)->create();

        /** @var WalletService $walletService */
        $walletService = new WalletService();
        $this->assertEquals(0, $walletService->balance($user));
    }

    public function testCreditor()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var WalletService $walletService */
        $walletService = new WalletService();
        $this->assertEquals(0, $walletService->balance($user));

        $walletService->creditor($user, 1000, 1, "");

        $this->assertEquals(1000, $walletService->balance($user));
        $this->assertDatabaseHas((new Wallet())->getTable(), [
            'user_id' => $user->getId(),
            'user_type' => $user->getType(),
            'type' => 1,
            'creditor' => 1000,
            'debtor' => 0,
            'description' => ""
        ]);
    }
}
