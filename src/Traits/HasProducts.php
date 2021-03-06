<?php

namespace MayIFit\Extension\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use MayIFit\Extension\Shop\Models\Product;

/**
 * Trait HasProducts
 *
 * @package MayIFit\Extension\Shop
 */
trait HasProducts
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongstoMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongstoMany(Product::class);
    }
}
