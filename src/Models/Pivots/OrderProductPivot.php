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

/**
 * Class OrderProductPivot
 *
 * @package MayIFit\Extension\Shop
 */
class OrderProductPivot extends Pivot
{
    use HasUsers;
    use HasReseller;
    use HasProduct;

    protected $gaurded = [];
    protected $table = 'order_product';

    protected $attributes = [
        'quantity_transferred' => 0
    ];

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
    public function pricing(): BelongsTo
    {
        return $this->belongsTo(ProductPricing::class, 'product_pricing_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discount(): BelongsTo
    {
        return $this->belongsTo(ProductDiscount::class, 'product_discount_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function hasPreviousUnShippedOrders()
    {
        return OrderProductPivot::where([
            ['product_id', '=', $this->product_id],
            ['order_id', '!=', $this->order_id],
            ['declined', false]
        ])->where('created_at', '<', $this->created_at)->whereNull('shipped_at')->get();
    }

    public function canBeShipped(): bool
    {
        if ($this->quantity == $this->quantity_transferred || $this->quantity <= 0 || $this->shipped_at || $this->order->sent_to_courier_service || $this->declined) {
            return false;
        }
        $prevShipments = $this->hasPreviousUnShippedOrders();

        $previouslyOrderedQuantity = 0;

        if (!$prevShipments->isEmpty()) {
            $prevShipments->map(function ($orderProduct) use (&$previouslyOrderedQuantity) {
                $previouslyOrderedQuantity += $orderProduct->quantity;
            });
        }

        if ($this->product->stock - $previouslyOrderedQuantity <= 0) {
            return false;
        }

        return $this->product->stock > 0;
    }
}
