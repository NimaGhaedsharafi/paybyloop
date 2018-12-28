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
use App\Services\Gift\Exceptions\Expired;
use App\Services\Gift\Exceptions\InvalidCode;
use App\Services\Gift\Exceptions\Overused;
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
        $gift = Gift::where('code', $code)->lockForUpdate()->first();
        /** @var GiftLog $log */
        $log = new GiftLog();
        $log->gift_id = $gift->id ?? 0;
        $log->code = $code;
        $log->user_id = $userId;

        if ($gift === null) {
            $log->applied_at = null;
            $log->save();
            throw new InvalidCode();
        }

        if ($gift->isExpired()) {
            throw new Expired();
        }
        if ($gift->isOverused()) {
            throw new Overused();
        }

        $log->applied_at = Carbon::now();
        $log->save();

        // increment usage
        $gift->used = $gift->used + 1;
        $gift->save();

        event(new GiftUsed($userId, $gift->id));
        return $gift;
    }
}