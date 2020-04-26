<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use MayIFit\Extension\Shop\Traits\HasProducts;
use MayIFit\Extension\Shop\Traits\HasCustomer;

class Order extends Model
{
    use HasProducts, HasCustomer;

    public $fillable = ["extra_information"];

    public static function boot()
    {
        parent::boot();

        self::creating(function($model) {
            $model->order_token = Str::random(40);
            $sum_net_value = 0;
            $sum_value = 0;
            $order_quantity = 0;
            foreach ($model->products as $product) {
                $sum_net_value += $product->net_price;
                $sum_value += ($product->net_price * (1 + ($product->vat / 100))) * $product->quantity;
                $order_quantity += $product->quantity;
            }
            $model->net_value = $sum_net_value;
            $model->value = $sum_value;
            return $model;
        });
    }

}
