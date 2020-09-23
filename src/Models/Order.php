<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use MayIFit\Extension\Shop\Traits\HasCustomers;
use MayIFit\Extension\Shop\Traits\HasReseller;
use MayIFit\Extension\Shop\Traits\HasOrderStatus;
use MayIFit\Extension\Shop\Scopes\DescendingIdOrderScope;
use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;

/**
 * Class Order
 *
 * @package MayIFit\Extension\Shop
 */
class Order extends Model
{
    use SoftDeletes;
    use HasCustomers;
    use HasReseller;
    use HasOrderStatus;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'order_id_prefix',
        'transport_cost',
        'extra_information',
        'discount_percentage',
        'payment_type',
        'delivery_type',
        'paid',
        'closed',
        'sent_to_courier_service',
        'invoice_number'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'net_value' => 0.00,
        'gross_value' => 0.00,
        'discount_percentage' => 0.00,
        'quantity' => 0,
        'paid' => false,
        'sent_to_courier_service' => null,
        'closed' => false,
        'currency' => 'HUF',
        'items_transferred' => 0,
        'transport_cost' => 0.00,
        'items_ordered' => 0,
        'quantity_transferred' => 0
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new DescendingIdOrderScope);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongstoMany(Product::class)
            ->using(OrderProductPivot::class)
            ->withPivot([
                'id',
                'product_pricing_id',
                'product_discount_id',
                'is_wholesale',
                'net_value',
                'gross_value',
                'vat',
                'quantity',
                'shipped_at',
                'declined',
                'quantity_transferred',
            ])->withTimestamps();
    }

    public function recalculateValues(): void
    {
        $this->net_value = 0;
        $this->gross_value = 0;
        $this->quantity = 0;
        $this->items_ordered = 0;
        $this->products->map(function ($product) {
            $this->net_value += round($product->pivot->net_value * $product->pivot->quantity, 2, PHP_ROUND_HALF_EVEN);
            $this->gross_value += round($product->pivot->gross_value * $product->pivot->quantity, 2, PHP_ROUND_HALF_EVEN);
            $this->quantity += $product->pivot->quantity;
            $this->items_ordered++;
        });
        $this->save();
    }

    public function getOrderCanBeShippedAttribute(): bool
    {
        if ($this->sent_to_courier_service) {
            return false;
        }

        $canBeShipped = $this->products->filter(function ($product) {
            return $product->pivot->canBeShipped();
        });
        $this->can_be_shipped = $canBeShipped->count() > 0;
        $dirty = $this->getDirty();
        if (isset($dirty['can_be_shipped'])) {
            $this->update();
        }
        return $canBeShipped->count() > 0;
    }

    public function getFullOrderCanBeShippedAttribute(): bool
    {
        if ($this->sent_to_courier_service) {
            return false;
        }

        $canBeShipped = $this->products->filter(function ($product) {
            return $product->pivot->canBeShipped();
        });

        return $canBeShipped->count() === $this->products->count();
    }
}
