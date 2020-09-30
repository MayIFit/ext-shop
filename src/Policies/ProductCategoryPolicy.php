<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use MayIFit\Extension\Shop\Models\ProductCategory;

/**
 * Class ProductCategoryPolicy
 *
 * @package MayIFit\Extension\Shop
 */
class ProductCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the can view any product categories.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function viewAny($authModel)
    {
        return $authModel->hasPermission('product-category.list');
    }

    /**
     * Determine whether the can view the product category.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductCategory  $productCategory
     * @return mixed
     */
    public function view($authModel, ProductCategory $productCategory)
    {
        return $authModel->hasPermission('product-category.view');
    }

    /**
     * Determine whether the can create product categories.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function create($authModel)
    {
        return $authModel->hasPermission('product-category.create');
    }

    /**
     * Determine whether the can update the product category.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductCategory  $productCategory
     * @return mixed
     */
    public function update($authModel, ProductCategory $productCategory)
    {
        return $authModel->hasPermission('product-category.update') ||
            $productCategory->createdBy->id === $authModel->id;
    }

    /**
     * Determine whether the can delete the product category.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductCategory  $productCategory
     * @return mixed
     */
    public function delete($authModel, ProductCategory $productCategory)
    {
        return $authModel->hasPermission('product-category.delete') ||
            $productCategory->createdBy->id === $authModel->id;;
    }

    /**
     * Determine whether the can restore the product category.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductCategory  $productCategory
     * @return mixed
     */
    public function restore($authModel, ProductCategory $productCategory)
    {
        return false;
    }

    /**
     * Determine whether the can permanently delete the product category.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductCategory  $productCategory
     * @return mixed
     */
    public function forceDelete($authModel, ProductCategory $productCategory)
    {
        return false;
    }
}
