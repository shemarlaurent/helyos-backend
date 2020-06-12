<?php

namespace App\Listeners\Seller;

use App\Events\Seller\PricedOrderReceived;
use App\Notifications\Seller\NewPricedProductOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSellerPricedProductOrder
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
     * @param  PricedOrderReceived  $event
     * @return void
     */
    public function handle(PricedOrderReceived $event)
    {
        $product = json_decode($event->order);

        $event->seller->notify(new NewPricedProductOrder($product));


    }
}
