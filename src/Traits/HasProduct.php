<?php

namespace MayIFit\Extension\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;

/**
 * Trait HasProduct
 *
 * @package MayIFit\Extension\Shop
 */
trait HasProduct
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    
}
