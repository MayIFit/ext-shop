<?php

namespace MayIFit\Extension\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use MayIFit\Extension\Shop\Models\Order;

/**
 * Class HasOrders
 *
 * @package MayIFit\Extension\Shop\Traits
 */
trait HasOrders {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders(): BelongsToMany {
        return $this->belongsToMany(Order::class);
    }
}