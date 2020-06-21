<?php

namespace MayIFit\Extension\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use MayIFit\Extension\Shop\Models\Reseller;

/**
 * Class HasOrderer
 *
 * @package MayIFit\Extension\Shop\Traits
 */
trait HasReseller {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reseller(): BelongsTo {
        return $this->belongsTo(Reseller::class);
    }
}