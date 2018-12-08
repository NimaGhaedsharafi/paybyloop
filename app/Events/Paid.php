<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Paid
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private $user;
    private $vendor;
    private $amount;

    /**
     * Create a new event instance.
     *
     * @param $user
     * @param $vendor
     * @param $amount
     */
    public function __construct($user, $vendor, $amount)
    {
        $this->user = $user;
        $this->vendor = $vendor;
        $this->amount = $amount;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
