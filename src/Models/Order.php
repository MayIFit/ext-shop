<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

use MayIFit\Extension\Shop\Notifications\OrderStatusUpdate;
use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;
use MayIFit\Extension\Shop\Models\OrderStatus;
use MayIFit\Extension\Shop\Traits\HasCustomer;
use MayIFit\Extension\Shop\Traits\HasOrderStatus;

class Order extends Model
{
    use HasCustomer, HasOrderStatus;

    public $fillable = ['extra_information', 'discount_percentage'];
    protected $with = ['customers'];

    protected $attributes = [
        'net_value' => 0.00,
        'gross_value' => 0.00,
        'discount_percentage' => 0.00,
        'quantity' => 0,
        'paid' => false,
        'currency' => 'HUF'
    ];

    public static function booted() {
        self::creating(function(Model $model) {
            $model->token = Str::random(40);
            $model->orderStatus()->associate(OrderStatus::first());
            return $model;
        });

        self::saved(function(Model $model) {
            if ($model->orderStatus->send_notification) {
                // $customer = $model->customers()->where('billing', false)->first();
                // $customer->notify(new OrderStatusUpdate($model));
            }
        });
    }

    public function products(): BelongsToMany {
        return $this->belongstoMany(Product::class)
        ->using(OrderProductPivot::class)
        ->withPivot(['id', 'quantity', 'product_pricing_id']);
    }
}
