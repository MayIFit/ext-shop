<?php

namespace MayIFit\Ext\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use MayIFit\Ext\Shop\Models\Category;

/**
 * Class HasCategories
 *
 * @package MayIFit\Ext\Shop\Traits
 */
trait HasCategories {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongstoMany
     */
    public function categories(): BelongsToMany {
        return $this->belongstoMany(Category::class);
    }
}