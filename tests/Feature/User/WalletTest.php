<?php

namespace Tests\Feature\User;

use App\Events\Paid;
use App\Services\Wallet\TransactionTypes;
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
            $wallet->creditor($this->user, rand(1, 10) * 1000, TransactionTypes::IPG, "");
        }


        $this->json('GET', route('v1.user.wallet.list'))
            ->assertOk()
            ->assertJsonCount(5);
    }

    /**
     * @test
     */
    public function pay_to_a_vendor_should_work()
    {
        $this->impersonate();
        $this->createVendor();

        $wallet = new WalletService();
        $wallet->creditor($this->user, 5000, 1, "");

        $this->expectsEvents(Paid::class);
        $this->json('POST', route('v1.user.wallet.pay'), [
            'vendor_id' => $this->vendor->vendor_id,
            'amount' => 2000
        ])->assertOk();

        $this->assertEquals(3000, $wallet->balance($this->user));
        $this->assertEquals(2000, $wallet->balance($this->vendor));

        $this->assertDatabaseHas('receipts', [
            'user_id' => $this->user->id,
            'vendor_id' => $this->vendor->id,
            'amount' => 2000,
            'total' => 2000,
        ]);
    }

    /**
     * @test
     */
    public function pay_fails_when_user_has_not_sufficient_credit()
    {
        $this->impersonate();
        $this->createVendor();

        $wallet = new WalletService();

        $this->json('POST', route('v1.user.wallet.pay'), [
            'vendor_id' => $this->vendor->vendor_id,
            'amount' => 2000
        ])->isRedirect();

        $this->assertEquals(0, $wallet->balance($this->user));
        $this->assertEquals(0, $wallet->balance($this->vendor));
    }

    /**
     * @test
     */
    public function pay_fails_when_vendor_does_not_exist()
    {
        $this->impersonate();

        $this->json('POST', route('v1.user.wallet.pay'), [
            'vendor_id' => 'gibberish',
            'amount' => 2000
        ])->assertStatus(404);
    }

    /**
     * @test
     */
    public function payment_fails_when_vendor_id_or_amount_is_missing()
    {
        $this->impersonate();

        $this->json('POST', route('v1.user.wallet.pay'), [
            'vendor_id' => 'gibberish',
            'amount' => 2000
        ])->assertStatus(404);
    }

    /**
     * @test
     */
    public function get_current_balance()
    {
        $this->impersonate();

        $this->json('get', route('v1.user.wallet.balance'))
            ->assertStatus(200)
            ->assertJson(['balance' => 0]);
    }

    /**
     * @test
     */
    public function pay_to_a_vendor_with_a_valid_voucher_code_should_work()
    {
        $this->impersonate();
        $this->createVendor();
        $voucher = $this->createVoucher([
            'code' => 'loop',
            'absolute' => 1000,
            'percent' => 0
        ]);

        $wallet = new WalletService();
        $wallet->creditor($this->user, 5000, 1, "");

        $this->json('POST', route('v1.user.wallet.pay'), [
            'vendor_id' => $this->vendor->vendor_id,
            'amount' => 2000,
            'voucher_code' => $voucher->code,
        ])->assertOk();

        $this->assertEquals(4000, $wallet->balance($this->user));
        $this->assertEquals(2000, $wallet->balance($this->vendor));

        $this->assertDatabaseHas('receipts', [
            'user_id' => $this->user->id,
            'vendor_id' => $this->vendor->id,
            'amount' => 1000,
            'total' => 2000,
        ]);
    }

    /**
     * @test
     */
    public function check_voucher_code_works()
    {
        $this->impersonate();
        $this->createVendor();
        $voucher = $this->createVoucher([
            'code' => 'loop',
            'absolute' => 1000,
            'percent' => 0
        ]);

        $wallet = new WalletService();
        $wallet->creditor($this->user, 5000, 1, "");

        $this->json('POST', route('v1.user.wallet.voucher'), [
            'vendor_id' => $this->vendor->vendor_id,
            'amount' => 2000,
            'voucher_code' => $voucher->code,
        ])->assertOk()->assertJson([
            'amount' => 1000
        ]);
    }
}
