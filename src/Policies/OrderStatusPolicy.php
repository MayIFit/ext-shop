<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use MayIFit\Extension\Shop\Models\OrderStatus;

/**
 * Class OrderStatusPolicy
 *
 * @package MayIFit\Extension\Shop
 */
class OrderStatusPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the can view any order statuses.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function viewAny($authModel)
    {
        return $authModel->hasPermission('order-status.list');
    }

    /**
     * Determine whether the can view the order status.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\OrderStatus  $orderStatus
     * @return mixed
     */
    public function view($authModel, OrderStatus $orderStatus)
    {
        return $authModel->hasPermission('order-status.view');
    }

    /**
     * Determine whether the can create order statuses.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function create($authModel)
    {
        return $authModel->hasPermission('order-status.create');
    }

    /**
     * Determine whether the can update the order status.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\OrderStatus  $orderStatus
     * @return mixed
     */
    public function update($authModel, OrderStatus $orderStatus)
    {
        return $authModel->hasPermission('order-status.update');
    }

    /**
     * Determine whether the can delete the order status.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\OrderStatus  $orderStatus
     * @return mixed
     */
    public function delete($authModel, OrderStatus $orderStatus)
    {
        return $authModel->hasPermission('order-status.delete');
    }

    /**
     * Determine whether the can restore the order status.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\OrderStatus  $orderStatus
     * @return mixed
     */
    public function restore($authModel, OrderStatus $orderStatus)
    {
        return false;
    }

    /**
     * Determine whether the can permanently delete the order status.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\OrderStatus  $orderStatus
     * @return mixed
     */
    public function forceDelete($authModel, OrderStatus $orderStatus)
    {
        return false;
    }
}
