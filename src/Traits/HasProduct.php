<?php

namespace MayIFit\Extension\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\Belongsto;

use MayIFit\Extension\Shop\Models\Product;

/**
 * Class HasProduct
 *
 * @package MayIFit\Extension\Shop\Traits
 */
trait HasProduct {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\Belongsto
     */
    public function product(): Belongsto {
        return $this->belongsTo(Product::class);
    }
}