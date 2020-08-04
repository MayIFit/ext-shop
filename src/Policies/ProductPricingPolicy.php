<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use MayIFit\Extension\Shop\Models\ProductPricing;

class ProductPricingPolicy
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
        return $user->tokenCan('product-pricing.list');
    }

    /**
     * Determine whether the user can view the product pricing.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $model
     * @return mixed
     */
    public function view(User $user, ProductPricing $model)
    {
        return $user->tokenCan('product-pricing.view');
    }

    /**
     * Determine whether the user can create product reviews.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->tokenCan('product-pricing.create');
    }

    /**
     * Determine whether the user can update the product pricing.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $model
     * @return mixed
     */
    public function update(User $user, ProductPricing $model)
    {
        return $user->tokenCan('product-pricing.update') || $model->createdBy->id === $user->id;
    }

    /**
     * Determine whether the user can delete the product pricing.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $model
     * @return mixed
     */
    public function delete(User $user, ProductPricing $model)
    {
        return $user->tokenCan('product-pricing.delete') || $model->createdBy->id === $user->id;
    }

    /**
     * Determine whether the user can restore the product pricing.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $model
     * @return mixed
     */
    public function restore(User $user, ProductPricing $model)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the product pricing.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductPricing  $model
     * @return mixed
     */
    public function forceDelete(User $user, ProductPricing $model)
    {
        return false;
    }
}
