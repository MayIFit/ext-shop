<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;
use MayIFit\Extension\Shop\Traits\HasCustomers;
use MayIFit\Extension\Shop\Traits\HasReseller;
use MayIFit\Extension\Shop\Traits\HasOrderStatus;

class Order extends Model
{
    use SoftDeletes, HasCustomers, HasReseller, HasOrderStatus;

    public $fillable = ['order_id_prefix', 'transport_cost', 'extra_information', 'discount_percentage', 'payment_type', 'delivery_type', 'paid', 'closed', 'sent_to_courier_service', 'invoice_number'];

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
    
    public function products(): BelongsToMany {
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

    public function recalculateValues(): void {
        $this->net_value = 0;
        $this->gross_value = 0;
        $this->products->map(function($product) {
            $this->net_value += round($product->pivot->net_value * $product->pivot->quantity, 2, PHP_ROUND_HALF_EVEN);
            $this->gross_value += round($product->pivot->gross_value * $product->pivot->quantity, 2, PHP_ROUND_HALF_EVEN);
        });
    }

    public function getOrderCanBeShippedAttribute(): bool {
        if ($this->sent_to_courier_service || $this->items_transferred === $this->items_ordered) {
            return false;
        }

        $canBeShipped = $this->products->filter(function($product) {
            return $product->pivot->canBeShipped();
        });

        return count($canBeShipped) > 0;
    }

    public function getFullOrderCanBeShippedAttribute(): bool {
        if ($this->sent_to_courier_service || $this->items_transferred === $this->items_ordered) {
            return false;
        }

        $canBeShipped = $this->products->filter(function($product) {
            return $product->pivot->canBeShipped();
        });

        return $canBeShipped->count() === $this->products->count();
    }
}
