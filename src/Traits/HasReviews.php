<?php

namespace MayIFit\Extension\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;

use MayIFit\Extension\Shop\Models\ProductReview;

/**
 * Trait HasReviews
 *
 * @package MayIFit\Extension\Shop
 */
trait HasReviews {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews(): HasMany {
        return $this->hasMany(ProductReview::class);
    }
}