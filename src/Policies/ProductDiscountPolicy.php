<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use MayIFit\Extension\Shop\Models\ProductDiscount;

class ProductDiscountPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any product reviews.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->tokenCan('product-discount.list');
    }

    /**
     * Determine whether the user can view the product discount.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $productDiscount
     * @return mixed
     */
    public function view(User $user, ProductPricing $productDiscount)
    {
        return $user->tokenCan('product-discount.view');
    }

    /**
     * Determine whether the user can create product reviews.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->tokenCan('product-discount.create');
    }

    /**
     * Determine whether the user can update the product discount.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $productDiscount
     * @return mixed
     */
    public function update(User $user, ProductPricing $productDiscount)
    {
        return $user->tokenCan('product-discount.update') || $productDiscount->createdBy->id === $user->id;
    }

    /**
     * Determine whether the user can delete the product discount.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $productDiscount
     * @return mixed
     */
    public function delete(User $user, ProductPricing $productDiscount)
    {
        return $user->tokenCan('product-discount.delete') || $productDiscount->createdBy->id === $user->id;
    }

    /**
     * Determine whether the user can restore the product discount.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $productDiscount
     * @return mixed
     */
    public function restore(User $user, ProductPricing $productDiscount)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the product discount.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $productDiscount
     * @return mixed
     */
    public function forceDelete(User $user, ProductPricing $productDiscount)
    {
        return false;
    }
}
