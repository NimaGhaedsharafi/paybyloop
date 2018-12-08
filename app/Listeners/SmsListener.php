<?php

namespace App\Listeners;

use App\Services\Notification\SmsService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SmsListener
{
    /**
     * @var SmsService
     */
    private $smsService;

    /**
     * Create the event listener.
     *
     * @param SmsService $smsService
     */
    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
    }
}
