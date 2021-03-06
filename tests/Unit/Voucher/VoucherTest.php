<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 12/16/18
 * Time: 3:06 PM
 */

namespace Tests\Unit\Voucher;

use App\Services\Voucher\Exceptions\AmountIsLessThanMinimumLimit;
use App\Services\Voucher\Exceptions\InvalidVoucherCode;
use App\Services\Voucher\Exceptions\VoucherExpired;
use App\Services\Voucher\VoucherService;
use App\User;
use App\UserWhitelist;
use App\Vendor;
use App\VendorWhitelist;
use App\Voucher;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;


/**
 * Class VoucherTest
 * @package Tests\Unit\Voucher
 */
class VoucherTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function create_voucher_works_fine()
    {
        $title = 'My Awesome Campaign';
        $code = 'Loop';
        $percent = rand(10, 90);
        $absolute = rand(1, 5) * 1000;
        $total_use = rand(1, 100);
        $per_user = rand(1, 5);
        $cap = $absolute * 2;
        $min = $absolute;
        $only_on_first = rand(0, 1);

        /** @var VoucherService $service */
        $service = app(VoucherService::class);
        $voucherCode = $service->create($title, $code, $percent, $absolute, $total_use, $per_user, $cap, $min, $only_on_first);

        $this->assertEquals($code, $voucherCode);

        $this->assertDatabaseHas('vouchers', compact(
            'title', 'code', 'percent',
            'absolute', 'total_use_time', 'per_user_time',
            'cap', 'min', 'only_on_first',
            'is_enabled', 'whitelist_parent_id')
        );
    }

    /**
     * @test
     */
    public function user_should_be_eligible_once_voucher_has_no_limitation()
    {
        /** @var Voucher $voucher */
        $voucher = factory(Voucher::class)->create();
        $vendor = factory(Vendor::class)->create();
        /** @var User $user */
        $user = factory(User::class)->create();
        $amount = $voucher->min * 2;

        /** @var VoucherService $service */
        $service = app(VoucherService::class);
        $result = $service->canUse($user, $voucher->code, $vendor, $amount);
        $this->assertNotNull($result);
    }

    /**
     * @test
     */
    public function eligible_calculates_based_on_amount()
    {
        /** @var Voucher $voucher */
        $voucher = factory(Voucher::class)->create([
            'absolute' => 1000,
            'percent' => 10,
            'min' => 0,
            'cap' => 0,
        ]);
        $vendor = factory(Vendor::class)->create();
        /** @var User $user */
        $user = factory(User::class)->create();
        $amount = 50000;

        /** @var VoucherService $service */
        $service = app(VoucherService::class);
        $result = $service->canUse($user, $voucher->code, $vendor, $amount);
        $this->assertEquals($voucher->absolute + $amount * ($voucher->percent / 100), $result);
    }


    /**
     * @test
     */
    public function once_cap_exists_it_should_be_applied()
    {
        /** @var Voucher $voucher */
        $voucher = factory(Voucher::class)->create([
            'absolute' => 1000,
            'percent' => 100,
            'min' => 0,
            'cap' => 5000,
        ]);
        $vendor = factory(Vendor::class)->create();
        /** @var User $user */
        $user = factory(User::class)->create();
        $amount = 50000;

        /** @var VoucherService $service */
        $service = app(VoucherService::class);
        $result = $service->canUse($user, $voucher->code, $vendor, $amount);
        $this->assertEquals($voucher->cap, $result);
    }

    /**
     * @test
     */
    public function at_most_amount_would_be_the_result()
    {
        /** @var Voucher $voucher */
        $voucher = factory(Voucher::class)->create([
            'absolute' => 1000,
            'percent' => 1000,
        ]);
        $vendor = factory(Vendor::class)->create();
        /** @var User $user */
        $user = factory(User::class)->create();
        $amount = 50000;

        /** @var VoucherService $service */
        $service = app(VoucherService::class);
        $result = $service->canUse($user, $voucher->code, $vendor, $amount);
        $this->assertEquals($amount, $result);
    }


    /**
     * @test
     */
    public function once_voucher_has_min_it_should_be_applied()
    {
        /** @var Voucher $voucher */
        $voucher = factory(Voucher::class)->create();
        /** @var Vendor $vendor */
        $vendor = factory(Vendor::class)->create();
        /** @var User $user */
        $user = factory(User::class)->create();
        $amount = $voucher->min * 2;

        /** @var VoucherService $service */
        $service = app(VoucherService::class);
        $result = $service->canUse($user, $voucher->code, $vendor, $amount);
        $this->assertNotNull($result);

        $amount = $vendor->min - 1000;
        $this->expectException(AmountIsLessThanMinimumLimit::class);
        $service->canUse($user, $voucher->code, $vendor, $amount);

    }

    /**
     * @test
     */
    public function once_voucher_is_not_enabled_it_should_not_be_applied()
    {
        /** @var Voucher $voucher */
        $voucher = factory(Voucher::class)->create(['is_enabled' => 0]);
        /** @var Vendor $vendor */
        $vendor = factory(Vendor::class)->create();
        /** @var User $user */
        $user = factory(User::class)->create();
        $amount = $voucher->min * 2;

        /** @var VoucherService $service */
        $service = app(VoucherService::class);
        $this->expectException(VoucherExpired::class);
        $service->canUse($user, $voucher->code, $vendor, $amount);

    }
    
    /**
     * @test
     */
    public function invalid_voucher_code_throws_exception()
    {
        /** @var Vendor $vendor */
        $vendor = factory(Vendor::class)->create();
        /** @var User $user */
        $user = factory(User::class)->create();

        /** @var VoucherService $service */
        $service = app(VoucherService::class);
        $this->expectException(InvalidVoucherCode::class);
        $service->canUse($user, 'random-code', $vendor, 1000);
    }

    /**
     * @test
     */
    public function voucher_eligibility_for_no_whitelisted_voucher_is_true()
    {
        /** @var Voucher $voucher */
        $voucher = factory(Voucher::class)->create();
        /** @var Vendor $vendor */
        $vendor = factory(Vendor::class)->create();

        /** @var VoucherService $service */
        $service = app(VoucherService::class);
        $this->assertTrue($service->isVendorEligible($voucher->id, $vendor));
    }

    /**
     * @test
     */
    public function voucher_eligibility_for_whitelisted_voucher_is_false()
    {
        /** @var Voucher $voucher */
        $voucher = factory(Voucher::class)->create();
        /** @var Vendor $vendor */
        $vendor = factory(Vendor::class)->create();
        /** @var VendorWhitelist $voucher */
        $whitelist = factory(VendorWhitelist::class)->create([
            'voucher_id' => $voucher->id,
        ]);

        /** @var VoucherService $service */
        $service = app(VoucherService::class);
        $this->assertFalse($service->isVendorEligible($voucher->id, $vendor));
    }

    /**
     * @test
     */
    public function voucher_eligibility_for_a_whitelisted_voucher_and_vendor_is_true()
    {
        /** @var Voucher $voucher */
        $voucher = factory(Voucher::class)->create();
        /** @var Vendor $vendor */
        $vendor = factory(Vendor::class)->create();
        /** @var VendorWhitelist $voucher */
        $whitelist = factory(VendorWhitelist::class)->create([
            'voucher_id' => $voucher->id,
            'vendor_id' => $vendor->id,
        ]);

        /** @var VoucherService $service */
        $service = app(VoucherService::class);
        $this->assertTrue($service->isVendorEligible($voucher->id, $vendor));
    }
    /**
     * @test
     */
    public function voucher_eligibility_for_no_whitelisted_user_is_true()
    {
        /** @var Voucher $voucher */
        $voucher = factory(Voucher::class)->create();
        /** @var User $vendor */
        $user = factory(User::class)->create();

        /** @var VoucherService $service */
        $service = app(VoucherService::class);
        $this->assertTrue($service->isUserEligible($voucher->id, $user));
    }

    /**
     * @test
     */
    public function voucher_user_eligibility_for_whitelisted_voucher_is_false()
    {
        /** @var Voucher $voucher */
        $voucher = factory(Voucher::class)->create();
        /** @var User $user */
        $user = factory(User::class)->create();
        /** @var VendorWhitelist $voucher */
        factory(UserWhitelist::class)->create([
            'voucher_id' => $voucher->id,
        ]);

        /** @var VoucherService $service */
        $service = app(VoucherService::class);
        $this->assertFalse($service->isUserEligible($voucher->id, $user));
    }

    /**
     * @test
     */
    public function voucher_user_eligibility_for_a_whitelisted_voucher_and_user_is_true()
    {
        /** @var Voucher $voucher */
        $voucher = factory(Voucher::class)->create();
        /** @var User $user */
        $user = factory(User::class)->create();
        /** @var VendorWhitelist $voucher */
        factory(VendorWhitelist::class)->create([
            'voucher_id' => $voucher->id,
            'vendor_id' => $user->id,
        ]);

        /** @var VoucherService $service */
        $service = app(VoucherService::class);
        $this->assertTrue($service->isUserEligible($voucher->id, $user));
    }
}