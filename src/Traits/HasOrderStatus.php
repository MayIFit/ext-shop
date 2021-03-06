<?php

namespace MayIFit\Extension\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use MayIFit\Extension\Shop\Models\OrderStatus;

/**
 * Trait HasOrderStatus
 *
 * @package MayIFit\Extension\Shop
 */
trait HasOrderStatus
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function orderStatus(): belongsTo
    {
        return $this->belongsTo(OrderStatus::class);
    }
}
