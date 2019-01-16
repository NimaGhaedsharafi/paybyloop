<?php

namespace App\Jobs;

use App\Listeners\SmsListener;
use App\Services\Notification\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Class AsyncSMS
 * @package App\Jobs
 */
class AsyncOTPSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $recipients;
    private $code;

    /**
     * Create a new job instance.
     *
     * @param $recipients
     * @param $code
     */
    public function __construct($recipients, $code)
    {
        $this->recipients = $recipients;
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $service = new SmsService();
        $service->otp($this->recipients, $this->code);
    }
}
