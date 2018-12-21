<?php

namespace App\Listeners;


use App\Events\Paid;
use App\Jobs\AsyncSMS;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SmsListener {

    use DispatchesJobs;
    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            Paid::class,
            static::class . "@notifyVendor"
        );
    }

    /**
     * @param Paid $paid
     */
    public function notifyVendor(Paid $paid)
    {
        $text = trans('sms.paid', [
            'name' => $paid->getUser()->getName(),
            'amount' => $paid->getAmount(),
            'reference' => $paid->getReference()
        ]);

        $this->dispatch(new AsyncSMS($paid->getVendor()->getOwnerPhoneNumber(), $text));
    }
}
