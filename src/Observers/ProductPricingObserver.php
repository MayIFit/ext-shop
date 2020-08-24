<?php

namespace MayIFit\Extension\Shop\Observers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use MayIFit\Extension\Shop\Models\ProductPricing;

/**
 * Class ProductPricingObserver
 *
 * @package MayIFit\Extension\Shop
 */
class ProductPricingObserver
{
    /**
     * Handle the ProductPricing "creating" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $model
     * @return void
     */
    public function creating(ProductPricing $model): void {
        $model->createdBy()->associate(Auth::user());
        if (!$model->available_from) {
            $model->available_from = Carbon::now();
        }
        if (!$model->wholesale_price) {
            $model->wholesale_price = $model->base_price;
        }
    }

    /**
     * Handle the ProductPricing "created" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $model
     * @return void
     */
    public function created(ProductPricing $model): void {
        //
    }

    /**
     * Handle the ProductPricing "updated" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $model
     * @return void
     */
    public function updated(ProductPricing $model): void {
        $model->updatedBy()->associate(Auth::user());
    }

    /**
     * Handle the ProductPricing "deleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $model
     * @return void
     */
    public function deleted(ProductPricing $model): void {
        //
    }

    /**
     * Handle the ProductPricing "forceDeleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $model
     * @return void
     */
    public function forceDeleted(ProductPricing $model): void {
        //
    }
}