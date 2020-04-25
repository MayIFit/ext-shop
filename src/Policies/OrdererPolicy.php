<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use MayIFit\Core\Permission\Models\User;
use MayIFit\Extension\Shop\Models\Orderer;

class OrdererPolicy
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
        return $user->hasPermission('orderer.list');
    }

    /**
     * Determine whether the user can view the orderer.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Orderer  $orderer
     * @return mixed
     */
    public function view(User $user, Orderer $orderer)
    {
        return $user->hasPermission('orderer.view') ||
            $user->id === $orderer->orderer;
    }

    /**
     * Determine whether the user can create translations.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the orderer.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Orderer  $orderer
     * @return mixed
     */
    public function update(User $user, Orderer $orderer)
    {
        return $user->hasPermission('orderer.update') ||
            $user->id === $orderer->orderer;
    }

    /**
     * Determine whether the user can delete the orderer.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Orderer  $orderer
     * @return mixed
     */
    public function delete(User $user, Orderer $orderer)
    {
        return $user->hasPermission('orderer.delete') ||
            $user->id === $orderer->orderer;
    }

    /**
     * Determine whether the user can restore the orderer.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Orderer  $orderer
     * @return mixed
     */
    public function restore(User $user, Orderer $orderer)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the orderer.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\Orderer  $orderer
     * @return mixed
     */
    public function forceDelete(User $user, Orderer $orderer)
    {
        return false;
    }
}
