<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 9/5/18
 * Time: 6:53 PM
 */

namespace App\Services\Voucher;


use App\Services\Voucher\Events\VoucherRedeemed;
use App\Voucher;
use App\VoucherLog;
use Carbon\Carbon;

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

    public function redeem($userId, $code)
    {
        /** @var Voucher $voucher */
        $voucher = Voucher::where('code', $code)->first();
        /** @var VoucherLog $log */
        $log = new VoucherLog();
        $log->voucher_id = $voucher->id ?? 0;
        $log->code = $code;
        $log->user_id = $userId;

        if ($voucher === null) {
            $log->applied_at = null;
            $log->save();
            return null;
        }

        $log->applied_at = Carbon::now();
        $log->save();
        event(new VoucherRedeemed($userId, $voucher->id));
        return $voucher;
    }
}