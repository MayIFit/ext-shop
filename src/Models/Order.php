<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

use MayIFit\Extension\Shop\Notifications\OrderStatusUpdate;
use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;
use MayIFit\Extension\Shop\Models\Order;
use MayIFit\Extension\Shop\Models\OrderStatus;
use MayIFit\Extension\Shop\Events\OrderAccepted;
use MayIFit\Extension\Shop\Traits\HasCustomers;
use MayIFit\Extension\Shop\Traits\HasOrderStatus;

class Order extends Model
{
    use HasCustomers, HasOrderStatus;

    public $fillable = ['extra_information', 'discount_percentage', 'payment_type', 'paid', 'sent_to_courier_service'];
    protected $with = ['customers', 'products'];

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

        // TODO: figure out, how to send notificaiton for related customer

        static::updated(function (Model $model) {
            
            if ($model->orderStatus->id === 3) {
                event(new OrderAccepted($model));
            }
        });

        static::saved(function (Model $model) {
            self::mergeOrders($model);
            return $model;
        });
    }

    public function products(): BelongsToMany {
        return $this->belongstoMany(Product::class)
        ->using(OrderProductPivot::class)
        ->withPivot(['id', 'quantity', 'product_pricing_id']);
    }
}
