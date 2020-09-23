<?php

namespace MayIFit\Extension\Shop\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

use MayIFit\Extension\Shop\Models\Order;

/**
 * Trait HasOrders
 *
 * @package MayIFit\Extension\Shop
 */
trait HasOrders
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getRecentOrders(): HasMany
    {
        return $this->orders()->where('created_at', '>=', Carbon::now()->subDays(5));
    }
}
