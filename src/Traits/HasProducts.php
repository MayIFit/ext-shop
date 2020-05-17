<?php

namespace MayIFit\Extension\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;

/**
 * Class HasProducts
 *
 * @package MayIFit\Extension\Shop\Traits
 */
trait HasProducts {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongstoMany
     */
    public function products(): BelongsToMany {
        return $this->belongstoMany(Product::class);
    }
}