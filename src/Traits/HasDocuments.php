<?php

namespace MayIFit\Extension\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

use MayIFit\Extension\Shop\Models\Document;

/**
 * Class HasDocuments
 *
 * @package MayIFit\Extension\Shop\Traits
 */
trait HasDocuments {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function documents(): MorphToMany {
        return $this->morphToMany(Document::class, 'entity');
    }
}