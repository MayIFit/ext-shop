<?php

namespace MayIFit\Extension\Shop\Observers;

use Illuminate\Support\Facades\Auth;

use MayIFit\Extension\Shop\Models\ProductCategory;

class ProductCategoryObserver
{
    /**
     * Handle the ProductCategory "created" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ProductCategory  $model
     * @return void
     */
    public function created(ProductCategory $model): void {
        $model->createdBy()->associate(Auth::user());
    }

    /**
     * Handle the ProductCategory "updated" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ProductCategory  $model
     * @return void
     */
    public function updated(ProductCategory $model): void {
        $model->updatedBy()->associate(Auth::user());
    }

    /**
     * Handle the ProductCategory "deleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ProductCategory  $model
     * @return void
     */
    public function deleted(ProductCategory $model): void {
        //
    }

    /**
     * Handle the ProductCategory "forceDeleted" event.
     *
     * @param  \MayIFit\Extension\Shop\Models\ProductCategory  $model
     * @return void
     */
    public function forceDeleted(ProductCategory $model): void {
        //
    }
}