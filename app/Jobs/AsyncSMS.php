<?php

namespace App\Jobs;

use App\Listeners\SmsListener;
use App\Services\Notification\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AsyncSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $recipients;
    private $text;

    /**
     * Create a new job instance.
     *
     * @param $recipients
     * @param $text
     */
    public function __construct($recipients, $text)
    {
        $this->recipients = $recipients;
        $this->text = $text;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $service = new SmsService();
        $service->send($this->recipients, $this->text);
    }
}
