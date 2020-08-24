<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use MayIFit\Extension\Shop\Models\ProductReview;

/**
 * Class ProductReviewPolicy
 *
 * @package MayIFit\Extension\Shop
 */
class ProductReviewPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any product reviews.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the product review.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductReview  $product-review
     * @return mixed
     */
    public function view(User $user, ProductReview $model)
    {
        return true;
    }

    /**
     * Determine whether the user can create product reviews.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the product review.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductReview  $model
     * @return mixed
     */
    public function update(User $user, ProductReview $model)
    {
        return $user->tokenCan('product-review.update') || $model->createdBy->id === $user->id;
    }

    /**
     * Determine whether the user can delete the product review.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductReview  $model
     * @return mixed
     */
    public function delete(User $user, ProductReview $model)
    {
        return $user->tokenCan('product-review.delete') || $model->createdBy->id === $user->id;
    }

    /**
     * Determine whether the user can restore the product review.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductReview  $model
     * @return mixed
     */
    public function restore(User $user, ProductReview $model)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the product review.
     *
     * @param  \App\Models\User  $user
     * @param  \MayIFit\Extension\Shop\Models\ProductReview  $model
     * @return mixed
     */
    public function forceDelete(User $user, ProductReview $model)
    {
        return false;
    }
}
