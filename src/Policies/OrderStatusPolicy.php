<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use MayIFit\Core\Permission\Models\User;
use MayIFit\Extension\Shop\Models\OrderStatus;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any order statuses.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('orderStatus.list');
    }

    /**
     * Determine whether the user can view the order status.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\OrderStatus  $orderStatus
     * @return mixed
     */
    public function view(User $user, OrderStatus $orderStatus)
    {
        return $user->hasPermission('orderStatus.view');
    }

    /**
     * Determine whether the user can create order statuses.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('orderStatus.create');
    }

    /**
     * Determine whether the user can update the order status.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\OrderStatus  $orderStatus
     * @return mixed
     */
    public function update(User $user, OrderStatus $orderStatus)
    {
        return $user->hasPermission('orderStatus.update');
    }

    /**
     * Determine whether the user can delete the order status.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\OrderStatus  $orderStatus
     * @return mixed
     */
    public function delete(User $user, OrderStatus $orderStatus)
    {
        return $user->hasPermission('orderStatus.delete');
    }

    /**
     * Determine whether the user can restore the order status.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
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
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\OrderStatus  $orderStatus
     * @return mixed
     */
    public function forceDelete(User $user, OrderStatus $orderStatus)
    {
        return false;
    }
}
