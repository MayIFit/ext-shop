<?php

namespace MayIFit\Extension\Shop\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use MayIFit\Extension\Shop\Events\OrderAccepted;
use MayIFit\Extension\Shop\Listeners\SendOrderDataToWMS;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        OrderAccepted::class => [
            SendOrderDataToWMS::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}