<?php

namespace MayIFit\Extension\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use MayIFit\Extension\Shop\Models\OrderStatus;

/**
 * Class HasStatus
 *
 * @package MayIFit\Extension\Shop\Traits
 */
trait HasStatus {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function status(): belongsTo {
        return $this->belongsTo(OrderStatus::class);
    }
}