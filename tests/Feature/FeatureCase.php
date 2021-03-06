<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 8/24/18
 * Time: 10:36 PM
 */

namespace Tests\Feature;


use App\User;
use App\Vendor;
use App\Gift;
use App\Voucher;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * Class FeatureCase
 * @package Tests\Feature
 */
class FeatureCase extends TestCase
{
    /** @var User */
    protected $user;

    /** @var Vendor */
    protected $vendor;

    /** @var Voucher */
    protected $voucher;

    /**
     * impersonate into a new user
     * @param array $options
     * @return User
     */
    public function impersonate($options = [])
    {
        Auth::login($this->user = factory(User::class)->create($options));

        return $this->user;
    }

    /**
     * @param array $options
     * @return Vendor
     */
    public function createVendor($options = [])
    {
        return $this->vendor = factory(Vendor::class)->create($options);
    }

    /**
     * @param array $options
     * @return Vendor
     */
    public function createVoucher($options = [])
    {
        return $this->voucher = factory(Voucher::class)->create($options);
    }
}