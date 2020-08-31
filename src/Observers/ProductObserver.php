<?php

namespace MayIFit\Extension\Shop\Observers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use MayIFit\Extension\Shop\Models\Product;

/**
 * Class ProductObserver
 *
 * @package MayIFit\Extension\Shop
 */
class ProductObserver
{
    /**
     * Handle the Product "creating" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Product  $model
     * @return void
     */
    public function creating(Product $model): void
    {
        $model->calculated_stock = $model->stock;
        $model->createdBy()->associate(Auth::user());
    }

    /**
     * Handle the Product "created" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Product  $model
     * @return void
     */
    public function created(Product $model): void
    {
        //
    }

    /**
     * Handle the Product "updating" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Product  $model
     * @return void
     */
    public function updating(Product $model): void
    {
        $dirty = $model->getDirty();
        $original = $model->getOriginal();

        if (isset($dirty['stock'])) {
            $source = $model->source;
            if (!$source) {
                $source = 'manual_edit';
            }
            unset($model->source);

            if (intval($dirty['stock']) !== intval($original['stock'])) {
                if (intval($dirty['stock']) > intval($original['stock'])) {
                    $model->calculated_stock += $dirty['stock'] - $original['stock'];
                }
                DB::insert('insert into stock_movements(product_id, original_quantity, incoming_quantity, difference, calculated_stock, source) values (?, ?, ?, ?, ?, ?)', [
                    $model->id,
                    $original['stock'],
                    $dirty['stock'],
                    intval($dirty['stock']) - intval($original['stock']),
                    $model->calculated_stock,
                    $source
                ]);
            }
        }
    }

    /**
     * Handle the Product "updated" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Product  $model
     * @return void
     */
    public function updated(Product $model): void
    {
        $model->updatedBy()->associate(Auth::user());
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Product  $model
     * @return void
     */
    public function deleted(Product $model): void
    {
        //
    }

    /**
     * Handle the Product "forceDeleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\Product  $model
     * @return void
     */
    public function forceDeleted(Product $model): void
    {
        //
    }
}
