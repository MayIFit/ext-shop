<?php

namespace MayIFit\Extension\Shop\Observers;

use Illuminate\Support\Facades\Auth;

use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;
use MayIFit\Extension\Shop\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "creating" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Product  $model
     * @return void
     */
    public function creating(Product $model): void {
        $model->createdBy()->associate(Auth::user());
    }

    /**
     * Handle the Product "created" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Product  $model
     * @return void
     */
    public function created(Product $model): void {
        //
    }

    /**
     * Handle the Product "updated" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Product  $model
     * @return void
     */
    public function updated(Product $model): void {
        $model->updatedBy()->associate(Auth::user());
        OrderProductPivot::where([['product_id', $model->id], ['can_be_shipped', false]])
        ->whereNull('shipped_at')
        ->get()->map(function($pivot) use($model) {
            if ($pivot->quantity <= $model->quantity) {
                $pivot->can_be_shipped = true;
            }
        });
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Product  $model
     * @return void
     */
    public function deleted(Product $model): void {
        OrderProductPivot::where([['product_id', $model->id]])
        ->whereNull('shipped_at')
        ->get()->map(function($pivot) use($model) {
            if ($pivot->quantity <= $model->quantity) {
                $pivot->can_be_shipped = false;
            }
        });
    }

    /**
     * Handle the Product "forceDeleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Product  $model
     * @return void
     */
    public function forceDeleted(Product $model): void {
        //
    }
}