<?php

namespace MayIFit\Extension\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use MayIFit\Extension\Shop\Models\Category;

/**
 * Class HasCategories
 *
 * @package MayIFit\Extension\Shop\Traits
 */
trait HasCategories {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongstoMany
     */
    public function categories(): BelongsToMany {
        return $this->belongstoMany(Category::class);
    }
}