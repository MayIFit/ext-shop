<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use MayIFit\Extension\Shop\Models\OrderStatus;

class OrderStatusPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any order statuses.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->tokenCan('order-status.list');
    }

    /**
     * Determine whether the user can view the order status.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\OrderStatus  $orderStatus
     * @return mixed
     */
    public function view(User $user, OrderStatus $orderStatus)
    {
        return $user->tokenCan('order-status.view');
    }

    /**
     * Determine whether the user can create order statuses.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->tokenCan('order-status.create');
    }

    /**
     * Determine whether the user can update the order status.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\OrderStatus  $orderStatus
     * @return mixed
     */
    public function update(User $user, OrderStatus $orderStatus)
    {
        return $user->tokenCan('order-status.update');
    }

    /**
     * Determine whether the user can delete the order status.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\OrderStatus  $orderStatus
     * @return mixed
     */
    public function delete(User $user, OrderStatus $orderStatus)
    {
        return $user->tokenCan('order-status.delete');
    }

    /**
     * Determine whether the user can restore the order status.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\OrderStatus  $orderStatus
     * @return mixed
     */
    public function restore(User $user, OrderStatus $orderStatus)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the order status.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\OrderStatus  $orderStatus
     * @return mixed
     */
    public function forceDelete(User $user, OrderStatus $orderStatus)
    {
        return false;
    }
}
