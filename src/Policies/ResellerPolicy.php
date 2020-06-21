<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use MayIFit\Extension\Shop\Models\Reseller;

class CustomerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any resellers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('reseller.list');
    }

    /**
     * Determine whether the user can view the reseller.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Reseller  $reseller
     * @return mixed
     */
    public function view(User $user, Reseller $reseller)
    {
        return $user->hasPermission('reseller.view') ||
            $user->id === $reseller->user->id;
    }

    /**
     * Determine whether the user can create customers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('reseller.create');
    }

    /**
     * Determine whether the user can update the reseller.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Reseller  $reseller
     * @return mixed
     */
    public function update(User $user, Reseller $reseller)
    {
        return $user->hasPermission('reseller.update') ||
            $user->id === $reseller->user->id;
    }

    /**
     * Determine whether the user can delete the reseller.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Reseller  $reseller
     * @return mixed
     */
    public function delete(User $user, Reseller $reseller)
    {
        return $user->hasPermission('reseller.delete') ||
            $user->id === $reseller->user->id;
    }

    /**
     * Determine whether the user can restore the reseller.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Reseller  $reseller
     * @return mixed
     */
    public function restore(User $user, Reseller $reseller)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the reseller.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Reseller  $reseller
     * @return mixed
     */
    public function forceDelete(User $user, Reseller $reseller)
    {
        return false;
    }
}
