<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;

class OrderProductPivotPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any order product-pivots.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->tokenCan('order-product-pivot.list');
    }

    /**
     * Determine whether the user can view the order product pivot.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return mixed
     */
    public function view(User $user, OrderProductPivot $model)
    {
        return $user->tokenCan('order-product-pivot.view');
    }

    /**
     * Determine whether the user can create orders.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the order product pivot.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return mixed
     */
    public function update(User $user, OrderProductPivot $model)
    {
        return $user->tokenCan('order-product-pivot.update');
    }

    /**
     * Determine whether the user can delete the order product pivot.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return mixed
     */
    public function delete(User $user, OrderProductPivot $model)
    {
        return $user->tokenCan('order-product-pivot.delete');
    }

    /**
     * Determine whether the user can restore the order product pivot.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return mixed
     */
    public function restore(User $user, OrderProductPivot $model)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the order product pivot.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $model
     * @return mixed
     */
    public function forceDelete(User $user, OrderProductPivot $model)
    {
        return false;
    }
}
