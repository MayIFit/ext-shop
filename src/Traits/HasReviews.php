<?php

namespace MayIFit\Extension\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use MayIFit\Extension\Shop\Models\ProductReview;

/**
 * Class HasReviews
 *
 * @package MayIFit\Extension\Shop\Traits
 */
trait HasReviews {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongstoMany
     */
    public function reviews(): BelongsToMany {
        return $this->belongstoMany(ProductReview::class);
    }
}