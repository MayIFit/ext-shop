<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use MayIFit\Core\Permission\Models\User;
use MayIFit\Extension\Shop\Models\Customer;

class CustomerPolicy
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
        return $user->hasPermission('customer.list');
    }

    /**
     * Determine whether the user can view the customer.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Customer  $customer
     * @return mixed
     */
    public function view(User $user, Customer $customer)
    {
        return $user->hasPermission('customer.view') ||
            $user->id === $customer->user->id;
    }

    /**
     * Determine whether the user can create customers.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the customer.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Customer  $customer
     * @return mixed
     */
    public function update(User $user, Customer $customer)
    {
        return $user->hasPermission('customer.update') ||
            $user->id === $customer->user->id;
    }

    /**
     * Determine whether the user can delete the customer.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Customer  $customer
     * @return mixed
     */
    public function delete(User $user, Customer $customer)
    {
        return $user->hasPermission('customer.delete') ||
            $user->id === $customer->user->id;
    }

    /**
     * Determine whether the user can restore the customer.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Customer  $customer
     * @return mixed
     */
    public function restore(User $user, Customer $customer)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the customer.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Customer  $customer
     * @return mixed
     */
    public function forceDelete(User $user, Customer $customer)
    {
        return false;
    }
}
