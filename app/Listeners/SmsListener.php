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
        $data = [
            $paid->getAmount(),
            $paid->getReference()
        ];

        $this->dispatch(new AsyncSMS($paid->getVendor()->getOwnerPhoneNumber(), $data, 'paid'));
    }
}
