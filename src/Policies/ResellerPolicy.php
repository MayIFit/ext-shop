<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use MayIFit\Extension\Shop\Models\Reseller;

/**
 * Class ResellerPolicy
 *
 * @package MayIFit\Extension\Shop
 */
class ResellerPolicy
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
        return $user->tokenCan('reseller.list');
    }

    /**
     * Determine whether the user can view the reseller.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Reseller  $model
     * @return mixed
     */
    public function view(User $user, Reseller $model)
    {
        return $user->tokenCan('reseller.view') ||
            $user->id === $model->user->id;
    }

    /**
     * Determine whether the user can create resellers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->tokenCan('reseller.create') || $user->is_reseller;
    }

    /**
     * Determine whether the user can update the reseller.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Reseller  $model
     * @return mixed
     */
    public function update(User $user, Reseller $model)
    {
        return $user->tokenCan('reseller.update') ||
            $user->id === $model->user->id;
    }

    /**
     * Determine whether the user can delete the reseller.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Reseller  $model
     * @return mixed
     */
    public function delete(User $user, Reseller $model)
    {
        return $user->tokenCan('reseller.delete') ||
            $user->id === $model->user->id;
    }

    /**
     * Determine whether the user can restore the reseller.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Reseller  $model
     * @return mixed
     */
    public function restore(User $user, Reseller $model)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the reseller.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Reseller  $model
     * @return mixed
     */
    public function forceDelete(User $user, Reseller $model)
    {
        return false;
    }
}
