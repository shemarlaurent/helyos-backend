<?php

namespace App\Providers;

use App\Events\Seller\PricedOrderReceived;
use App\Events\Seller\SellerCreated;
use App\Events\User\OrderCanceled;
use App\Events\User\OrderFulfilled;
use App\Listeners\Seller\SendSellerCreatedNotification;
use App\Listeners\Seller\SendSellerPricedProductOrder;
use App\Listeners\SendOrderCanceledNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SellerCreated::class => [
            SendSellerCreatedNotification::class
        ],

        PricedOrderReceived::class => [
            SendSellerPricedProductOrder::class
        ],

//        OrderCanceled::class => [
//            SendOrderCanceledNotification::class,
//        ],
//
//        OrderFulfilled::class => [
//            SendOrderCanceledNotification::class,
//        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
