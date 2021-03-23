<?php

namespace MayIFit\Extension\Shop\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use MayIFit\Core\Permission\Traits\HasCreators;

use MayIFit\Extension\Shop\Models\ProductPricing;
use MayIFit\Extension\Shop\Models\ProductDiscount;
use MayIFit\Extension\Shop\Traits\HasReseller;
use MayIFit\Extension\Shop\Traits\HasProduct;
use MayIFit\Extension\Shop\Traits\HasOrder;

/**
 * Class OrderProductPivot
 *
 * @package MayIFit\Extension\Shop
 */
class OrderProductPivot extends Pivot
{
    use HasCreators;
    use HasReseller;
    use HasProduct;
    use HasOrder;

    protected $table = 'order_product';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'quantity_transferred' => 0
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pricing(): BelongsTo
    {
        return $this->belongsTo(ProductPricing::class, 'product_pricing_id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discount(): BelongsTo
    {
        return $this->belongsTo(ProductDiscount::class, 'product_discount_id')->withTrashed();
    }

    public function previousUnShippedOrders()
    {
        return OrderProductPivot::where([
            ['product_id', '=', $this->product_id],
            ['order_id', '!=', $this->order_id],
            ['declined', false]
        ])->whereHas('order', function ($query) {
            return $query->whereNull('sent_to_courier_service');
        })->where('created_at', '<', $this->created_at)
            ->whereNull('shipped_at')->get();
    }

    public function canBeShipped(): bool
    {
        if ($this->quantity == $this->quantity_transferred || $this->quantity <= 0 || $this->shipped_at || $this->order->sent_to_courier_service || $this->declined) {
            return false;
        }

        $sumQuantity = $this->previousUnShippedOrders()->sum('quantity');

        return $this->product->stock - $sumQuantity > 0;
    }


    public function getAmountCanBeShipped(): int
    {

        $sumQuantity = $this->previousUnShippedOrders()->sum('quantity');

        $diff = $this->product->stock - $sumQuantity;
        $shippable = $diff >= $this->quantity ? $this->quantity : $diff;

        return  $shippable > 0 ? $shippable : 0;
    }
}
