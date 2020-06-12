<?php

namespace MayIFit\Extension\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;

use MayIFit\Extension\Shop\Models\Order;

/**
 * Class HasOrders
 *
 * @package MayIFit\Extension\Shop\Traits
 */
trait HasOrders {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany {
        return $this->hasMany(Order::class);
    }
}