<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use MayIFit\Extension\Shop\Models\Order;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any orders.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->tokenCan('order.list');
    }

    /**
     * Determine whether the user can view the order.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return mixed
     */
    public function view(User $user, Order $model)
    {
        return $user->tokenCan('order.view') ||
            $user->id === $model->customer->user->id;
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
     * Determine whether the user can update the order.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return mixed
     */
    public function update(User $user, Order $model)
    {
        return $user->tokenCan('order.update') ||
            $user->id === $model->customer->user->id;
    }

    /**
     * Determine whether the user can delete the order.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return mixed
     */
    public function delete(User $user, Order $model)
    {
        return $user->tokenCan('order.delete')  ||
            $user->id === $model->customer->user->id;
    }

    /**
     * Determine whether the user can restore the order.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return mixed
     */
    public function restore(User $user, Order $model)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the order.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Order  $model
     * @return mixed
     */
    public function forceDelete(User $user, Order $model)
    {
        return false;
    }
}
