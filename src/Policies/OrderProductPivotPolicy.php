<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;

/**
 * Class OrderProductPivotPolicy
 *
 * @package MayIFit\Extension\Shop
 */
class OrderProductPivotPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the can view any order product-pivots.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function viewAny($authModel)
    {
        return $authModel->hasPermission('order-product-pivot.list');
    }

    /**
     * Determine whether the can view the order product pivot.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $orderProductPivot
     * @return mixed
     */
    public function view($authModel, OrderProductPivot $orderProductPivot)
    {
        return $authModel->hasPermission('order-product-pivot.view');
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
     * Determine whether the can update the order product pivot.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $orderProductPivot
     * @return mixed
     */
    public function update($authModel, OrderProductPivot $orderProductPivot)
    {
        return $authModel->hasPermission('order-product-pivot.update');
    }

    /**
     * Determine whether the can delete the order product pivot.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $orderProductPivot
     * @return mixed
     */
    public function delete($authModel, OrderProductPivot $orderProductPivot)
    {
        return $authModel->hasPermission('order-product-pivot.delete');
    }

    /**
     * Determine whether the can restore the order product pivot.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $orderProductPivot
     * @return mixed
     */
    public function restore($authModel, OrderProductPivot $orderProductPivot)
    {
        return false;
    }

    /**
     * Determine whether the can permanently delete the order product pivot.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot  $orderProductPivot
     * @return mixed
     */
    public function forceDelete($authModel, OrderProductPivot $orderProductPivot)
    {
        return false;
    }
}
