<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use MayIFit\Extension\Shop\Models\Product;

/**
 * Class ProductPolicy
 *
 * @package MayIFit\Extension\Shop
 */
class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any products.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function viewAny($authModel)
    {
        return $authModel->hasPermission('product.list');
    }

    /**
     * Determine whether the user can view the product.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Product  $product
     * @return mixed
     */
    public function view($authModel, Product $product)
    {
        return $authModel->hasPermission('product.view');
    }

    /**
     * Determine whether the user can create products.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function create($authModel)
    {
        return $authModel->hasPermission('product.create');
    }

    /**
     * Determine whether the user can update the product.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Product  $product
     * @return mixed
     */
    public function update($authModel, Product $product)
    {
        return $authModel->hasPermission('product.update') ||
            $product->createdBy->id === $authModel->id;
    }

    /**
     * Determine whether the user can delete the product.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Product  $product
     * @return mixed
     */
    public function delete($authModel, Product $product)
    {
        return $authModel->hasPermission('product.delete') ||
            $product->createdBy->id === $authModel->id;
    }

    /**
     * Determine whether the user can restore the product.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Product  $product
     * @return mixed
     */
    public function restore($authModel, Product $product)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the product.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Product  $product
     * @return mixed
     */
    public function forceDelete($authModel, Product $product)
    {
        return false;
    }
}
