<?php

namespace App\Events;

use App\Receipt;
use App\User;
use App\Vendor;
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
    /** @var User */
    private $user;
    /** @var Vendor */
    private $vendor;
    /** @var Receipt */
    private $receipt;

    /**
     * Create a new event instance.
     *
     * @param $user
     * @param $vendor
     * @param $receipt
     */
    public function __construct($user, $vendor, $receipt)
    {
        $this->user = $user;
        $this->vendor = $vendor;
        $this->receipt = $receipt;
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

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return Vendor
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @param mixed $vendor
     */
    public function setVendor($vendor): void
    {
        $this->vendor = $vendor;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return number_format($this->receipt->total);
    }

    /**
     * @param mixed $receipt
     */
    public function setAmount($receipt): void
    {
        $this->receipt = $receipt;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->receipt->reference;
    }

}
