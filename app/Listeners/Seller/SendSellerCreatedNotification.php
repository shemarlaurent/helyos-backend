<?php

namespace App\Listeners\Seller;

use App\Events\Seller\SellerCreated;
use App\Notifications\Seller\WelcomeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSellerCreatedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SellerCreated  $event
     * @return void
     */
    public function handle(SellerCreated $event)
    {
        $event->seller->notify(new WelcomeNotification());
    }
}
