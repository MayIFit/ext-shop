<?php

namespace MayIFit\Extension\Shop\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use MayIFit\Core\Permission\Traits\HasUsers;

use MayIFit\Extension\Shop\Models\Order;
use MayIFit\Extension\Shop\Models\ProductPricing;
use MayIFit\Extension\Shop\Models\ProductDiscount;

class OrderProductPivot extends Pivot
{
    use HasUsers;

    protected $gaurded = [];
    protected $table = 'order_product';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'shipped_at' => 'datetime:Y-m-d h:i:s'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pricing(): BelongsTo {
        return $this->belongsTo(ProductPricing::class, 'product_pricing_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discount(): BelongsTo {
        return $this->belongsTo(ProductDiscount::class, 'product_discount_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }
}