<?php

namespace MayIFit\Extension\Shop\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\DB;

use MayIFit\Core\Permission\Traits\HasCreators;
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

    protected $with = [
        'product',
    ];

    protected $hidden = ['order'];

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

    public function getFifoAttribute(): int
    {
        return DB::table('order_product')->where([
            ['product_id', '=', $this->product_id],
            ['order_id', '!=', $this->order_id],
            ['order_product.declined', false],
            ['order_product.created_at', '<', $this->created_at]
        ])->join('orders', 'orders.id', 'order_product.order_id')
            ->whereNull('orders.sent_to_courier_service')
            ->sum('order_product.quantity');
    }

    public function canBeShipped(): bool
    {
        if ($this->shipped_at || $this->declined || $this->order->sent_to_courier_service) {
            return false;
        }

        return $this->product->stock - $this->fifo > 0;
    }


    public function getAmountCanBeShipped(): int
    {
        $diff = $this->product->stock - $this->fifo;
        $shippable = $diff >= $this->quantity ? $this->quantity : $diff;

        return $shippable > 0 ? $shippable : 0;
    }
}
