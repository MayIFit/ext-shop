<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use MayIFit\Extension\Shop\Models\ResellerGroup;

/**
 * Class ResellerGroupPolicy
 *
 * @package MayIFit\Extension\Shop
 */
class ResellerGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the can view any resellerGroups.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function viewAny($authModel)
    {
        return $authModel->hasPermission('reseller-group.list');
    }

    /**
     * Determine whether the can view the resellerGroup.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ResellerGroup  $resellerGroup
     * @return mixed
     */
    public function view($authModel, ResellerGroup $resellerGroup)
    {
        return $authModel->hasPermission('reseller-group.view');
    }

    /**
     * Determine whether the can create resellerGroups.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function create($authModel)
    {
        return $authModel->hasPermission('reseller-group.create');
    }

    /**
     * Determine whether the can update the resellerGroup.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ResellerGroup  $resellerGroup
     * @return mixed
     */
    public function update($authModel, ResellerGroup $resellerGroup)
    {
        return $authModel->hasPermission('reseller-group.update') ||
            $resellerGroup->createdBy->id === $authModel->id;
    }

    /**
     * Determine whether the can delete the resellerGroup.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ResellerGroup  $resellerGroup
     * @return mixed
     */
    public function delete($authModel, ResellerGroup $resellerGroup)
    {
        return $authModel->hasPermission('reseller-group.delete') ||
            $resellerGroup->createdBy->id === $authModel->id;;
    }

    /**
     * Determine whether the can restore the resellerGroup.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ResellerGroup  $resellerGroup
     * @return mixed
     */
    public function restore($authModel, ResellerGroup $resellerGroup)
    {
        return false;
    }

    /**
     * Determine whether the can permanently delete the resellerGroup.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ResellerGroup  $resellerGroup
     * @return mixed
     */
    public function forceDelete($authModel, ResellerGroup $resellerGroup)
    {
        return false;
    }
}
