<?php

namespace MayIFit\Extension\Shop\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider
 *
 * @package MayIFit\Extension\Shop
 */

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot() {
        parent::boot();
    }
}