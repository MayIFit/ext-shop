<?php

namespace MayIFit\Extension\Shop\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use MayIFit\Core\Permission\Traits\HasUsers;

use MayIFit\Extension\Shop\Models\Order;
use MayIFit\Extension\Shop\Models\ProductPricing;
use MayIFit\Extension\Shop\Models\ProductDiscount;
use MayIFit\Extension\Shop\Traits\HasReseller;
use MayIFit\Extension\Shop\Traits\HasProduct;

class OrderProductPivot extends Pivot
{
    use HasUsers, HasReseller, HasProduct;

    protected $gaurded = [];
    protected $table = 'order_product';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'shipped_at' => 'datetime:Y-m-d h:i:s',
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

    public function canBeShipped(): bool {
        if ($this->quantity === $this->quantity_transferred || $this->shipped_at) {
            return false;
        }

        $previouslyOrdered = OrderProductPivot::where([
            ['product_id', '=', $this->product_id],
            ['order_id', '!=', $this->order_id],
        ])->where('created_at', '<', $this->created_at)->whereNull('shipped_at')->first();

        if ($previouslyOrdered) {
            return false;
        }

        return $this->product->stock > 0;
    }
}