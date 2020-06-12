<?php

namespace App\Events\Seller;

use App\Seller;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SellerCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var Seller
     */
    public $seller;

    /**
     * Create a new event instance.
     *
     * @param Seller $seller
     */
    public function __construct(Seller $seller)
    {
        $this->seller = $seller;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
//    public function broadcastOn()
//    {
//        return new PrivateChannel('channel-name');
//    }
}
