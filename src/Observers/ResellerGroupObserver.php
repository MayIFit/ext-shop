<?php

namespace MayIFit\Extension\Shop\Observers;

use Illuminate\Support\Facades\Auth;

use MayIFit\Extension\Shop\Models\ResellerGroup;

/**
 * Class ResellerGroupObserver
 *
 * @package MayIFit\Extension\Shop
 */
class ResellerGroupObserver
{
    /**
     * Handle the ResellerGroup "created" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ResellerGroup  $model
     * @return void
     */
    public function created(ResellerGroup $model): void {
        $model->createdBy()->associate(Auth::user());
    }

    /**
     * Handle the ResellerGroup "updated" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ResellerGroup  $model
     * @return void
     */
    public function updated(ResellerGroup $model): void {
        $model->updatedBy()->associate(Auth::user());
    }

    /**
     * Handle the ResellerGroup "deleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ResellerGroup  $model
     * @return void
     */
    public function deleted(ResellerGroup $model): void {
        //
    }

    /**
     * Handle the ResellerGroup "forceDeleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ResellerGroup  $model
     * @return void
     */
    public function forceDeleted(ResellerGroup $model): void {
        //
    }
}