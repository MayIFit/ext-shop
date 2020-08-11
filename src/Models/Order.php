<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;
use MayIFit\Extension\Shop\Traits\HasCustomers;
use MayIFit\Extension\Shop\Traits\HasReseller;
use MayIFit\Extension\Shop\Traits\HasOrderStatus;

class Order extends Model
{
    use HasCustomers, HasReseller, HasOrderStatus;

    public $fillable = ['order_id_prefix', 'extra_information', 'discount_percentage', 'payment_type', 'delivery_type', 'paid', 'sent_to_courier_service'];

    protected $attributes = [
        'net_value' => 0.00,
        'gross_value' => 0.00,
        'discount_percentage' => 0.00,
        'quantity' => 0,
        'paid' => false,
        'currency' => 'HUF',
        'items_sent' => 0,
        'transport_cost' => 0.00,
    ];
    
    public function products(): BelongsToMany {
        return $this->belongstoMany(Product::class)
        ->using(OrderProductPivot::class)
        ->withPivot([
            'id',
            'quantity',
            'product_pricing_id',
            'product_discount_id',
            'net_value',
            'gross_value',
            'is_wholesale',
            'can_be_shipped',
            'shipped_at'
        ]);
    }

    public function recalculateValues(): void {
        $this->net_value = 0;
        $this->gross_value = 0;
        $this->products->map(function($product) {
            $this->net_value += $product->pivot->net_value * $product->pivot->quantity;
            $this->gross_value += $product->pivot->gross_value * $product->pivot->quantity;
        });

        $this->save();
    }
}
