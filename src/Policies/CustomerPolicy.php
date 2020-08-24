<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
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
     * Determine whether the user can view any customers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->tokenCan('customer.list');
    }

    /**
     * Determine whether the user can view the customer.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Customer  $model
     * @return mixed
     */
    public function view(User $user, Customer $model)
    {
        return $user->tokenCan('customer.view') ||
            $user->id === $model->user->id;
    }

    /**
     * Determine whether the user can create customers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the customer.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Customer  $model
     * @return mixed
     */
    public function update(User $user, Customer $model)
    {
        return $user->tokenCan('customer.update') ||
            $user->id === $model->user->id;
    }

    /**
     * Determine whether the user can delete the customer.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Customer  $model
     * @return mixed
     */
    public function delete(User $user, Customer $model)
    {
        return $user->tokenCan('customer.delete') ||
            $user->id === $model->user->id;
    }

    /**
     * Determine whether the user can restore the customer.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Customer  $model
     * @return mixed
     */
    public function restore(User $user, Customer $model)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the customer.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Customer  $model
     * @return mixed
     */
    public function forceDelete(User $user, Customer $model)
    {
        return false;
    }
}
