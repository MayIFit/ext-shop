<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use MayIFit\Core\Permission\Models\User;
use MayIFit\Extension\Shop\Models\ProductReview;

class ProductReviewPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any product reviews.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the product review.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductReview  $product-review
     * @return mixed
     */
    public function view(User $user, ProductReview $productReview)
    {
        return true;
    }

    /**
     * Determine whether the user can create product reviews.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the product review.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductReview  $productReview
     * @return mixed
     */
    public function update(User $user, ProductReview $productReview)
    {
        return $user->hasPermission('product-review.update') || $productReview->createdBy->id === $user->id;
    }

    /**
     * Determine whether the user can delete the product review.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductReview  $productReview
     * @return mixed
     */
    public function delete(User $user, ProductReview $productReview)
    {
        return $user->hasPermission('product-review.delete') || $productReview->createdBy->id === $user->id;
    }

    /**
     * Determine whether the user can restore the product review.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductReview  $productReview
     * @return mixed
     */
    public function restore(User $user, ProductReview $productReview)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the product review.
     *
     * @param  \MayIFit\Core\Permission\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductReview  $productReview
     * @return mixed
     */
    public function forceDelete(User $user, ProductReview $productReview)
    {
        return false;
    }
}
