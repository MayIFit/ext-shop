<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use MayIFit\Extension\Shop\Models\ResellerShopCart;

/**
 * Class ResellerShopCartPolicy
 *
 * @package MayIFit\Extension\Shop
 */
class ResellerShopCartPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any reseller-shop-carts.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->tokenCan('reseller-shop-cart.list');
    }

    /**
     * Determine whether the user can view the resellerShopCart.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ResellerShopCart  $model
     * @return mixed
     */
    public function view(User $user, ResellerShopCart $model)
    {
        return $user->tokenCan('reseller-shop-cart.view') ||
            $user->reseller->id === $model->reseller->id;
    }

    /**
     * Determine whether the user can create reseller-shop-carts.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->tokenCan('reseller-shop-cart.create') || $user->is_reseller;
    }

    /**
     * Determine whether the user can update the reseller-shop-cart.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ResellerShopCart  $model
     * @return mixed
     */
    public function update(User $user, ResellerShopCart $model)
    {
        return $user->tokenCan('reseller-shop-cart.update') ||
            $user->reseller->id === $model->reseller->id;
    }

    /**
     * Determine whether the user can delete the reseller-shop-cart.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ResellerShopCart  $model
     * @return mixed
     */
    public function delete(User $user, ResellerShopCart $model)
    {
        return $user->tokenCan('reseller-shop-cart.delete') ||
            $user->reseller->id === $model->reseller->id;
    }

    /**
     * Determine whether the user can restore the reseller-shop-cart.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ResellerShopCart  $model
     * @return mixed
     */
    public function restore(User $user, ResellerShopCart $model)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the reseller-shop-cart.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ResellerShopCart  $model
     * @return mixed
     */
    public function forceDelete(User $user, ResellerShopCart $model)
    {
        return false;
    }
}
