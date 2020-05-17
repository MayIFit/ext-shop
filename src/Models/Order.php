<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

use MayIFit\Extension\Shop\Models\OrderStatus;
use MayIFit\Extension\Shop\Traits\HasCustomer;
use MayIFit\Extension\Shop\Traits\HasOrderStatus;

class Order extends Model
{
    use HasCustomer, HasOrderStatus;

    public $fillable = ['extra_information', 'discount_percentage'];

    public static function booted() {
        self::creating(function(Model $model) {
            $model->token = Str::random(40);
            $model->net_value = 0;
            $model->gross_value = 0;
            $model->discount_percentage = 0;
            $model->quantity = 0;
            $model->orderStatus()->associate(OrderStatus::first());
            $model->paid = false;
            return $model;
        });
    }

    public function products(): BelongsToMany {
        return $this->belongstoMany(Product::class)
        ->using(OrderProductPivot::class)
        ->withPivot('quantity');
    }
}
