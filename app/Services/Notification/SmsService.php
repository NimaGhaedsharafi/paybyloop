<?php
/**
 * Created by PhpStorm.
 * User: nghaedsharafi
 * Date: 10/23/18
 * Time: 3:11 PM
 */

namespace App\Services\Notification;


use Illuminate\Support\Facades\Log;
use Kavenegar\KavenegarApi;

class SmsService
{
    /**
     * @param $recipient
     * @param $message
     */
    public function send($recipient, $message)
    {
        if (app()->environment('production') == false) {
            return ;
        }
        $recipient = is_array($recipient) ? $recipient : [$recipient];

        /** @var KavenegarApi $service */
        $service = new KavenegarApi(config('services.kavenegar.key'));
        try {
            $service->Send(config('services.kavenegar.sender'), $recipient, $message);
        } catch (\Exception $exception) {
            Log::error('SMS Service: ' . $exception->getMessage());
        }

        return ;
    }

    /**
     * @param $recipient
     * @param $code
     */
    public function otp($recipient, $code)
    {
        if (app()->environment('production') == false) {
            \Log::info('Pretend OTP SMS: ' . $recipient . ' ' . $code);
            return ;
        }

        /** @var KavenegarApi $service */
        $service = new KavenegarApi(config('services.kavenegar.key'));
        try {
            $service->VerifyLookup($recipient, $code, null, null, 'otp');
        } catch (\Exception $exception) {
            Log::error('SMS Service: ' . $exception->getMessage());
        }

        return ;
    }
}