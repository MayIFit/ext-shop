<?php

namespace MayIFit\Extension\Shop\Observers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use MayIFit\Extensiom\Shop\Models\ProductDiscount;

class ProductDiscountObserver
{
    /**
     * Handle the ProductDiscount "created" event.
     *
     * @param  \MayIFit\Extensiom\Shop\Models\ProductDiscount  $model
     * @return void
     */
    public function created(ProductDiscount $model): void {
        $model->createdBy()->associate(Auth::user());
        if (!$model->available_from) {
            $model->available_from = Carbon::now();
        }
    }

    /**
     * Handle the ProductDiscount "updated" event.
     *
     * @param  \MayIFit\Extensiom\Shop\Models\ProductDiscount  $model
     * @return void
     */
    public function updated(ProductDiscount $model): void {
        $model->updatedBy()->associate(Auth::user());
    }

    /**
     * Handle the ProductDiscount "deleted" event.
     *
     * @param  \MayIFit\Extensiom\Shop\Models\ProductDiscount  $model
     * @return void
     */
    public function deleted(ProductDiscount $model): void {
        //
    }

    /**
     * Handle the ProductDiscount "forceDeleted" event.
     *
     * @param  \MayIFit\Extensiom\Shop\Models\ProductDiscount  $model
     * @return void
     */
    public function forceDeleted(ProductDiscount $model): void {
        //
    }
}