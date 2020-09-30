<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use MayIFit\Extension\Shop\Models\Customer;

/**
 * Class CustomerPolicy
 *
 * @package MayIFit\Extension\Shop
 */
class CustomerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the can view any customers.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function viewAny($authModel)
    {
        return $authModel->hasPermission('customer.list');
    }

    /**
     * Determine whether the can view the customer.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Customer  $authModel
     * @return mixed
     */
    public function view($authModel, Customer $customer)
    {
        return $authModel->hasPermission('customer.view') ||
            $authModel->id === $customer->user->id;
    }

    /**
     * Determine whether the can create customers.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function create($authModel)
    {
        return true;
    }

    /**
     * Determine whether the can update the customer.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Customer  $authModel
     * @return mixed
     */
    public function update($authModel, Customer $customer)
    {
        return $authModel->hasPermission('customer.update') ||
            $authModel->id === $customer->user->id;
    }

    /**
     * Determine whether the can delete the customer.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Customer  $authModel
     * @return mixed
     */
    public function delete($authModel, Customer $customer)
    {
        return $authModel->hasPermission('customer.delete') ||
            $authModel->id === $customer->user->id;
    }

    /**
     * Determine whether the can restore the customer.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Customer  $authModel
     * @return mixed
     */
    public function restore($authModel, Customer $customer)
    {
        return false;
    }

    /**
     * Determine whether the can permanently delete the customer.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Customer  $authModel
     * @return mixed
     */
    public function forceDelete($authModel, Customer $customer)
    {
        return false;
    }
}
