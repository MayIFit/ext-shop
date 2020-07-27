<?php

namespace MayIFit\Extension\Shop\Observers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use MayIFit\Extension\Shop\Models\ProductCategoryDiscount;

class ProductCategoryDiscountObserver
{
    /**
     * Handle the ProductCategoryDiscount "created" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ProductCategoryDiscount  $model
     * @return void
     */
    public function created(ProductCategoryDiscount $model): void {
        $model->createdBy()->associate(Auth::user());
        if (!$model->available_from) {
            $model->available_from = Carbon::now();
        }
    }

    /**
     * Handle the ProductCategoryDiscount "updated" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ProductCategoryDiscount  $model
     * @return void
     */
    public function updated(ProductCategoryDiscount $model): void {
        $model->updatedBy()->associate(Auth::user());
    }

    /**
     * Handle the ProductCategoryDiscount "deleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ProductCategoryDiscount  $model
     * @return void
     */
    public function deleted(ProductCategoryDiscount $model): void {
        //
    }

    /**
     * Handle the ProductCategoryDiscount "forceDeleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ProductCategoryDiscount  $model
     * @return void
     */
    public function forceDeleted(ProductCategoryDiscount $model): void {
        //
    }
}