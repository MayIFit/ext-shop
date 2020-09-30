<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

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
     * Determine whether the can view any reseller-shop-carts.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function viewAny($authModel)
    {
        return $authModel->hasPermission('reseller-shop-cart.list');
    }

    /**
     * Determine whether the can view the resellerShopCart.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ResellerShopCart  $resellerShopCart
     * @return mixed
     */
    public function view($authModel, ResellerShopCart $resellerShopCart)
    {
        return $authModel->hasPermission('reseller-shop-cart.view') ||
            $resellerShopCart->reseller->id === $authModel->reseller->id;
    }

    /**
     * Determine whether the can create reseller-shop-carts.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function create($authModel)
    {
        return $authModel->hasPermission('reseller-shop-cart.create') ||
            $authModel->is_reseller;
    }

    /**
     * Determine whether the can update the reseller-shop-cart.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ResellerShopCart  $resellerShopCart
     * @return mixed
     */
    public function update($authModel, ResellerShopCart $resellerShopCart)
    {
        return $authModel->hasPermission('reseller-shop-cart.update') ||
            $authModel->reseller->id === $resellerShopCart->reseller->id;
    }

    /**
     * Determine whether the can delete the reseller-shop-cart.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ResellerShopCart  $resellerShopCart
     * @return mixed
     */
    public function delete($authModel, ResellerShopCart $resellerShopCart)
    {
        return $authModel->hasPermission('reseller-shop-cart.delete') ||
            $authModel->reseller->id === $resellerShopCart->reseller->id;
    }

    /**
     * Determine whether the can restore the reseller-shop-cart.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ResellerShopCart  $resellerShopCart
     * @return mixed
     */
    public function restore($authModel, ResellerShopCart $resellerShopCart)
    {
        return false;
    }

    /**
     * Determine whether the can permanently delete the reseller-shop-cart.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ResellerShopCart  $resellerShopCart
     * @return mixed
     */
    public function forceDelete($authModel, ResellerShopCart $resellerShopCart)
    {
        return false;
    }
}
