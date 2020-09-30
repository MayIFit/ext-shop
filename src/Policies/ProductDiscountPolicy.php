<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use MayIFit\Extension\Shop\Models\ProductDiscount;

/**
 * Class ProductDiscountPolicy
 *
 * @package MayIFit\Extension\Shop
 */
class ProductDiscountPolicy
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
        return $authModel->hasPermission('product-discount.list');
    }

    /**
     * Determine whether the can view the product discount.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductDiscount  $productDiscount
     * @return mixed
     */
    public function view($authModel, ProductDiscount $productDiscount)
    {
        return $authModel->hasPermission('product-discount.view');
    }

    /**
     * Determine whether the can create product reviews.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function create($authModel)
    {
        return $authModel->hasPermission('product-discount.create');
    }

    /**
     * Determine whether the can update the product discount.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductDiscount  $productDiscount
     * @return mixed
     */
    public function update($authModel, ProductDiscount $productDiscount)
    {
        return $authModel->hasPermission('product-discount.update') || $productDiscount->createdBy->id === $authModel->id;
    }

    /**
     * Determine whether the can delete the product discount.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductDiscount  $productDiscount
     * @return mixed
     */
    public function delete($authModel, ProductDiscount $productDiscount)
    {
        return $authModel->hasPermission('product-discount.delete') || $productDiscount->createdBy->id === $authModel->id;
    }

    /**
     * Determine whether the can restore the product discount.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductDiscount  $productDiscount
     * @return mixed
     */
    public function restore($authModel, ProductDiscount $productDiscount)
    {
        return false;
    }

    /**
     * Determine whether the can permanently delete the product discount.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductDiscount  $productDiscount
     * @return mixed
     */
    public function forceDelete($authModel, ProductDiscount $productDiscount)
    {
        return false;
    }
}
