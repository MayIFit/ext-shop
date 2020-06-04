<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use MayIFit\Core\Permission\Models\User;
use MayIFit\Extension\Shop\Models\ProductCategory;

class ProductCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any product categories.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('product-category.list');
    }

    /**
     * Determine whether the user can view the product category.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductCategory  $productCategory
     * @return mixed
     */
    public function view(User $user, ProductCategory $productCategory)
    {
        return $user->hasPermission('product-category.view');
    }

    /**
     * Determine whether the user can create product categories.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('product-category.create');
    }

    /**
     * Determine whether the user can update the product category.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductCategory  $productCategory
     * @return mixed
     */
    public function update(User $user, ProductCategory $productCategory)
    {
        return $user->hasPermission('product-category.update');
    }

    /**
     * Determine whether the user can delete the product category.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductCategory  $productCategory
     * @return mixed
     */
    public function delete(User $user, ProductCategory $productCategory)
    {
        return $user->hasPermission('product-category.delete');
    }

    /**
     * Determine whether the user can restore the product category.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductCategory  $productCategory
     * @return mixed
     */
    public function restore(User $user, ProductCategory $productCategory)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the product category.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductCategory  $productCategory
     * @return mixed
     */
    public function forceDelete(User $user, ProductCategory $productCategory)
    {
        return false;
    }
}
