<?php

namespace MayIFit\Ext\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use MayIFit\Ext\Shop\Models\Product;

/**
 * Class HasProducts
 *
 * @package MayIFit\Ext\Shop\Traits
 */
trait HasProducts {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongstoMany
     */
    public function products(): BelongsToMany {
        return $this->belongstoMany(Product::class);
    }
}