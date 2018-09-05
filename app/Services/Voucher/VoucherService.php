<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 9/5/18
 * Time: 6:53 PM
 */

namespace App\Services\Voucher;


use App\Voucher;

class VoucherService
{
    public function create($amount, $title, $expiresIn, $code, $maxUseTime)
    {
        /** @var Voucher $voucher */
        $voucher = new Voucher();

        $voucher->amount = $amount;
        $voucher->title = $title;
        $voucher->expires_in = $expiresIn;
        $voucher->code = $code;
        $voucher->max_use_time = $maxUseTime;
        $voucher->save();

        return $voucher->code;
    }
}