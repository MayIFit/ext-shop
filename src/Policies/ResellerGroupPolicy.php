<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use MayIFit\Extension\Shop\Models\ResellerGroup;

class ResellerGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any resellerGroups.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->tokenCan('reseller-group.list');
    }

    /**
     * Determine whether the user can view the resellerGroup.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ResellerGroup  $resellerGroup
     * @return mixed
     */
    public function view(User $user, ResellerGroup $resellerGroup)
    {
        return $user->tokenCan('reseller-group.view');
    }

    /**
     * Determine whether the user can create resellerGroups.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->tokenCan('reseller-group.create');
    }

    /**
     * Determine whether the user can update the resellerGroup.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ResellerGroup  $resellerGroup
     * @return mixed
     */
    public function update(User $user, ResellerGroup $resellerGroup)
    {
        return $user->tokenCan('reseller-group.update');
    }

    /**
     * Determine whether the user can delete the resellerGroup.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ResellerGroup  $resellerGroup
     * @return mixed
     */
    public function delete(User $user, ResellerGroup $resellerGroup)
    {
        return $user->tokenCan('reseller-group.delete');
    }

    /**
     * Determine whether the user can restore the resellerGroup.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ResellerGroup  $resellerGroup
     * @return mixed
     */
    public function restore(User $user, ResellerGroup $resellerGroup)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the resellerGroup.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ResellerGroup  $resellerGroup
     * @return mixed
     */
    public function forceDelete(User $user, ResellerGroup $resellerGroup)
    {
        return false;
    }
}
