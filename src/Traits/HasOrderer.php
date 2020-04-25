<?php

namespace MayIFit\Extension\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use MayIFit\Extension\Shop\Models\Orderer;

/**
 * Class HasOrderer
 *
 * @package MayIFit\Extension\Shop\Traits
 */
trait HasOrderer {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderer(): BelongsTo {
        return $this->belongsTo(Orderer::class);
    }
}