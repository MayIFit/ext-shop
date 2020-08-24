<?php

namespace MayIFit\Extension\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\Belongsto;

use MayIFit\Extension\Shop\Models\Product;

/**
 * Trait HasProduct
 *
 * @package MayIFit\Extension\Shop
 */
trait HasProduct {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\Belongsto
     */
    public function product(): Belongsto {
        return $this->belongsTo(Product::class);
    }
}