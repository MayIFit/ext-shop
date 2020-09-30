<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use MayIFit\Extension\Shop\Models\ProductPricing;

/**
 * Class ProductPricingPolicy
 *
 * @package MayIFit\Extension\Shop
 */
class ProductPricingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the can view any product reviews.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function viewAny($authModel)
    {
        return $authModel->hasPermission('product-pricing.list');
    }

    /**
     * Determine whether the can view the product pricing.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $productPricing
     * @return mixed
     */
    public function view($authModel, ProductPricing $productPricing)
    {
        return $authModel->hasPermission('product-pricing.view');
    }

    /**
     * Determine whether the can create product reviews.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function create($authModel)
    {
        return $authModel->hasPermission('product-pricing.create');
    }

    /**
     * Determine whether the can update the product pricing.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $productPricing
     * @return mixed
     */
    public function update($authModel, ProductPricing $productPricing)
    {
        return $authModel->hasPermission('product-pricing.update') ||
            $productPricing->createdBy->id === $authModel->id;
    }

    /**
     * Determine whether the can delete the product pricing.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $productPricing
     * @return mixed
     */
    public function delete($authModel, ProductPricing $productPricing)
    {
        return $authModel->hasPermission('product-pricing.delete') ||
            $productPricing->createdBy->id === $authModel->id;
    }

    /**
     * Determine whether the can restore the product pricing.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $productPricing
     * @return mixed
     */
    public function restore($authModel, ProductPricing $productPricing)
    {
        return false;
    }

    /**
     * Determine whether the can permanently delete the product pricing.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $productPricing
     * @return mixed
     */
    public function forceDelete($authModel, ProductPricing $productPricing)
    {
        return false;
    }
}
