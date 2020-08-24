<?php

namespace MayIFit\Extension\Shop\Observers;

use Illuminate\Support\Facades\Auth;

use MayIFit\Extension\Shop\Models\Reseller;

/**
 * Class ResellerObserver
 *
 * @package MayIFit\Extension\Shop
 */
class ResellerObserver
{
    /**
     * Handle the Reseller "created" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Reseller  $model
     * @return void
     */
    public function created(Reseller $model): void {
        if (!$model->user) {
            $model->user()->associate(Auth::user());
        }
    }

    /**
     * Handle the Reseller "updated" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Reseller  $model
     * @return void
     */
    public function updated(Reseller $model): void {
        //
    }

    /**
     * Handle the Reseller "deleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Reseller  $model
     * @return void
     */
    public function deleted(Reseller $model): void {
        //
    }

    /**
     * Handle the Reseller "forceDeleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Reseller  $model
     * @return void
     */
    public function forceDeleted(Reseller $model): void {
        //
    }
}