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
use App\Services\Voucher\Exceptions\VoucherExpired;
use App\User;
use App\Vendor;
use App\Voucher;


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
    public function isUserEligible(User $user, $code, Vendor $vendor, $amount)
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

        $result = $voucher->absolute + $amount * ($voucher->percent / 100.0);

        if ($voucher->cap != 0) {
            $result = min($voucher->cap, $result);
        }

        return min($result, $amount);
    }
}