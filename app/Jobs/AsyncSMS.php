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
    private $fast;

    /**
     * Create a new job instance.
     *
     * @param $recipients
     * @param $text
     * @param $fast
     */
    public function __construct($recipients, $text, $fast = '')
    {
        $this->recipients = $recipients;
        $this->text = $text;
        $this->fast = $fast;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $service = new SmsService();
        if ($this->fast != '') {
            $service->fast($this->recipients, $this->fast, $this->text);
            return ;
        }
        $service->send($this->recipients, $this->text);
    }
}
