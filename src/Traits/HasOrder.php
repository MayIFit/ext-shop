<?php

namespace MayIFit\Extension\Shop\Traits;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use MayIFit\Extension\Shop\Models\Order;

/**
 * Trait HasOrder
 *
 * @package MayIFit\Extension\Shop
 */
trait HasOrder
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
