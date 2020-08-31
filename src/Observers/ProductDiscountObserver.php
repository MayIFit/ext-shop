<?php

namespace MayIFit\Extension\Shop\Observers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use MayIFit\Extension\Shop\Models\ProductDiscount;

/**
 * Class ProductDiscountObserver
 *
 * @package MayIFit\Extension\Shop
 */
class ProductDiscountObserver
{
    /**
     * Handle the ProductDiscount "creating" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ProductDiscount  $model
     * @return void
     */
    public function creating(ProductDiscount $model): void
    {
        $model->createdBy()->associate(Auth::user());
        if (!$model->available_from) {
            $model->available_from = Carbon::now();
        }
    }

    /**
     * Handle the ProductDiscount "created" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ProductDiscount  $model
     * @return void
     */
    public function created(ProductDiscount $model): void
    {
        //
    }

    /**
     * Handle the ProductDiscount "updated" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ProductDiscount  $model
     * @return void
     */
    public function updated(ProductDiscount $model): void
    {
        $model->updatedBy()->associate(Auth::user());
    }

    /**
     * Handle the ProductDiscount "deleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ProductDiscount  $model
     * @return void
     */
    public function deleted(ProductDiscount $model): void
    {
        //
    }

    /**
     * Handle the ProductDiscount "forceDeleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ProductDiscount  $model
     * @return void
     */
    public function forceDeleted(ProductDiscount $model): void
    {
        //
    }
}
