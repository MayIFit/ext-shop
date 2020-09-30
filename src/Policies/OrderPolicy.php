<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use MayIFit\Extension\Shop\Models\Order;

/**
 * Class OrderPolicy
 *
 * @package MayIFit\Extension\Shop
 */
class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the can view any orders.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function viewAny($authModel)
    {
        return $authModel->hasPermission('order.list');
    }

    /**
     * Determine whether the can view the order.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Order  $order
     * @return mixed
     */
    public function view($authModel, Order $order)
    {
        return $authModel->hasPermission('order.view') ||
            $authModel->id === $order->customer->user->id;
    }

    /**
     * Determine whether the can create orders.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function create($authModel)
    {
        return true;
    }

    /**
     * Determine whether the can update the order.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Order  $order
     * @return mixed
     */
    public function update($authModel, Order $order)
    {
        return $authModel->hasPermission('order.update') ||
            $authModel->id === $order->customer->user->id;
    }

    /**
     * Determine whether the can delete the order.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Order  $order
     * @return mixed
     */
    public function delete($authModel, Order $order)
    {
        return $authModel->hasPermission('order.delete')  ||
            $authModel->id === $order->customer->user->id;
    }

    /**
     * Determine whether the can restore the order.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Order  $order
     * @return mixed
     */
    public function restore($authModel, Order $order)
    {
        return false;
    }

    /**
     * Determine whether the can permanently delete the order.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Order  $order
     * @return mixed
     */
    public function forceDelete($authModel, Order $order)
    {
        return false;
    }
}
