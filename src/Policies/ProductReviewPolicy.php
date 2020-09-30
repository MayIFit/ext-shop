<?php

namespace MayIFit\Extension\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

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
     * Determine whether the can view any product reviews.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function viewAny($authModel)
    {
        return true;
    }

    /**
     * Determine whether the can view the product review.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductReview  $productReview
     * @return mixed
     */
    public function view($authModel, ProductReview $productReview)
    {
        return true;
    }

    /**
     * Determine whether the can create product reviews.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @return mixed
     */
    public function create($authModel)
    {
        return true;
    }

    /**
     * Determine whether the can update the product review.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductReview  $productReview
     * @return mixed
     */
    public function update($authModel, ProductReview $productReview)
    {
        return $authModel->hasPermission('product-review.update') ||
            $productReview->createdBy->id === $authModel->id;
    }

    /**
     * Determine whether the can delete the product review.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductReview  $productReview
     * @return mixed
     */
    public function delete($authModel, ProductReview $productReview)
    {
        return $authModel->hasPermission('product-review.delete') ||
            $productReview->createdBy->id === $authModel->id;
    }

    /**
     * Determine whether the can restore the product review.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductReview  $productReview
     * @return mixed
     */
    public function restore($authModel, ProductReview $productReview)
    {
        return false;
    }

    /**
     * Determine whether the can permanently delete the product review.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $authModel
     * @param  \MayIFit\Extension\Shop\Models\ProductReview  $productReview
     * @return mixed
     */
    public function forceDelete($authModel, ProductReview $productReview)
    {
        return false;
    }
}
