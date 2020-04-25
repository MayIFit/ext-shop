<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use MayIFit\Core\Permission\Models\User;
use MayIFit\Extension\Shop\Models\Order;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any translations.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('order.list');
    }

    /**
     * Determine whether the user can view the order.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Order  $order
     * @return mixed
     */
    public function view(User $user, Order $order)
    {
        return $user->hasPermission('order.view') ||
            $user->id === $order->customer->user->id;
    }

    /**
     * Determine whether the user can create orders.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the order.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Order  $order
     * @return mixed
     */
    public function update(User $user, Order $order)
    {
        return $user->hasPermission('order.update') ||
            $user->id === $order->customer->user->id;
    }

    /**
     * Determine whether the user can delete the order.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Order  $order
     * @return mixed
     */
    public function delete(User $user, Order $order)
    {
        return $user->hasPermission('order.delete')  ||
            $user->id === $order->customer->user->id;
    }

    /**
     * Determine whether the user can restore the order.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Order  $order
     * @return mixed
     */
    public function restore(User $user, Order $order)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the order.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Order  $order
     * @return mixed
     */
    public function forceDelete(User $user, Order $order)
    {
        return false;
    }
}
