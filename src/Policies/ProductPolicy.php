<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
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
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->tokenCan('product.list');
    }

    /**
     * Determine whether the user can view the product.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Product  $model
     * @return mixed
     */
    public function view(User $user, Product $model)
    {
        return $user->tokenCan('product.view');
    }

    /**
     * Determine whether the user can create products.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->tokenCan('product.create');
    }

    /**
     * Determine whether the user can update the product.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Product  $model
     * @return mixed
     */
    public function update(User $user, Product $model)
    {
        return $user->tokenCan('product.update');
    }

    /**
     * Determine whether the user can delete the product.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Product  $model
     * @return mixed
     */
    public function delete(User $user, Product $model)
    {
        return $user->tokenCan('product.delete');
    }

    /**
     * Determine whether the user can restore the product.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Product  $model
     * @return mixed
     */
    public function restore(User $user, Product $model)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the product.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Product  $model
     * @return mixed
     */
    public function forceDelete(User $user, Product $model)
    {
        return false;
    }
}
