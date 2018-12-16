<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 9/5/18
 * Time: 6:53 PM
 */

namespace App\Services\Gift;


use App\Services\Gift\Events\GiftUsed;
use App\Gift;
use App\GiftLog;
use Carbon\Carbon;

class GiftService
{
    public function create($amount, $title, $expiresIn, $code, $maxUseTime)
    {
        /** @var Gift $gift */
        $gift = new Gift();

        $gift->amount = $amount;
        $gift->title = $title;
        $gift->expires_in = $expiresIn;
        $gift->code = $code;
        $gift->max_use_time = $maxUseTime;
        $gift->save();

        return $gift->code;
    }

    public function redeem($userId, $code)
    {
        /** @var Gift $gift */
        $gift = Gift::where('code', $code)->first();
        /** @var GiftLog $log */
        $log = new GiftLog();
        $log->gift_id = $gift->id ?? 0;
        $log->code = $code;
        $log->user_id = $userId;

        if ($gift === null) {
            $log->applied_at = null;
            $log->save();
            return null;
        }

        $log->applied_at = Carbon::now();
        $log->save();
        event(new GiftUsed($userId, $gift->id));
        return $gift;
    }
}