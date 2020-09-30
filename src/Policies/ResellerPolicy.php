<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

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
     * Determine whether the can view any resellers.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function viewAny($authModel)
    {
        return $authModel->hasPermission('reseller.list');
    }

    /**
     * Determine whether the can view the reseller.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Reseller  $reseller
     * @return mixed
     */
    public function view($authModel, Reseller $reseller)
    {
        return $authModel->hasPermission('reseller.view') ||
            $authModel->id === $reseller->user->id;
    }

    /**
     * Determine whether the can create resellers.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function create($authModel)
    {
        return $authModel->hasPermission('reseller.create') ||
            $authModel->is_reseller;
    }

    /**
     * Determine whether the can update the reseller.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Reseller  $reseller
     * @return mixed
     */
    public function update($authModel, Reseller $reseller)
    {
        return $authModel->hasPermission('reseller.update') ||
            $authModel->id === $reseller->user->id;
    }

    /**
     * Determine whether the can delete the reseller.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Reseller  $reseller
     * @return mixed
     */
    public function delete($authModel, Reseller $reseller)
    {
        return $authModel->hasPermission('reseller.delete') ||
            $authModel->id === $reseller->user->id;
    }

    /**
     * Determine whether the can restore the reseller.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Reseller  $reseller
     * @return mixed
     */
    public function restore($authModel, Reseller $reseller)
    {
        return false;
    }

    /**
     * Determine whether the can permanently delete the reseller.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\Reseller  $reseller
     * @return mixed
     */
    public function forceDelete($authModel, Reseller $reseller)
    {
        return false;
    }
}
