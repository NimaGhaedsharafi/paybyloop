<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 12/16/18
 * Time: 3:04 PM
 */

namespace App\Services\Voucher;

use App\Services\Voucher\Exceptions\AmountIsLessThanMinimumLimit;
use App\Services\Voucher\Exceptions\InvalidVoucherCode;
use App\Services\Voucher\Exceptions\UserException;
use App\Services\Voucher\Exceptions\VendorException;
use App\Services\Voucher\Exceptions\VoucherExpired;
use App\User;
use App\UserWhitelist;
use App\Vendor;
use App\VendorWhitelist;
use App\Voucher;
use Illuminate\Support\Collection;


/**
 * Class VoucherService
 * @package App\Services\Voucher
 */
class VoucherService
{
    /**
     * @param $title
     * @param $code
     * @param $percent
     * @param $absolute
     * @param $total_use
     * @param $per_user
     * @param $cap
     * @param $min
     * @param $only_on_first
     * @return string
     */
    public function create($title, $code, $percent, $absolute, $total_use, $per_user, $cap, $min, $only_on_first)
    {
        /** @var Voucher $voucher */
        $voucher = new Voucher();
        $voucher->title = $title;
        $voucher->code = $code;
        $voucher->percent = $percent;
        $voucher->absolute = $absolute;
        $voucher->total_use = $total_use;
        $voucher->per_user = $per_user;
        $voucher->cap = $cap;
        $voucher->min = $min;
        $voucher->only_on_first = $only_on_first;
        $voucher->is_enabled = true;
        $voucher->whitelist_parent_id = 0;
        $voucher->save();

        return $voucher->code;
    }

    /**
     * @param User $user
     * @param $code
     * @param Vendor $vendor
     * @param $amount
     * @return integer
     */
    public function canUse(User $user, $code, Vendor $vendor, $amount)
    {
        /** @var Voucher $voucher */
        $voucher = Voucher::where('code', $code)->first();

        if ($voucher === null) {
            throw new InvalidVoucherCode();
        }
        if ($voucher->is_enabled == 0) {
            throw new VoucherExpired();
        }
        if ($voucher->min > $amount) {
            throw new AmountIsLessThanMinimumLimit($voucher->min);
        }

        // check for whitelisting
        $whitelistId = $voucher->id;
        if ($voucher->whitelist_parent_id != 0) {
            $whitelistId = $voucher->whitelist_parent_id;
        }

        if ($this->isVendorEligible($whitelistId, $vendor) == false) {
            throw new VendorException();
        }

        if ($this->isUserEligible($whitelistId, $user) == false) {
            throw new UserException();
        }

        $result = $voucher->absolute + $amount * ($voucher->percent / 100.0);

        if ($voucher->cap != 0) {
            $result = min($voucher->cap, $result);
        }

        return min($result, $amount);
    }

    /**
     * @param $voucherId
     * @param Vendor $vendor
     * @return bool
     */
    public function isVendorEligible($voucherId, Vendor $vendor)
    {
        /** @var Collection $whitelist */
        $whitelist = VendorWhitelist::select('vendor_id')->where('voucher_id', $voucherId)->get();

        if ($whitelist->count() == 0) {
            return true;
        }

        return $whitelist->contains('vendor_id', $vendor->id);
    }

    /**
     * @param $voucherId
     * @param User $user
     * @return bool
     */
    public function isUserEligible($voucherId, User $user)
    {
        /** @var Collection $whitelist */
        $whitelist = UserWhitelist::select('user_id')->where('voucher_id', $voucherId)->get();

        if ($whitelist->count() == 0) {
            return true;
        }

        return $whitelist->contains('user_id', $user->id);
    }
}