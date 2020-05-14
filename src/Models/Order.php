<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use MayIFit\Extension\Shop\Traits\HasProducts;
use MayIFit\Extension\Shop\Traits\HasCustomer;
use MayIFit\Extension\Shop\Traits\HasOrderStatus;

class Order extends Model
{
    use HasProducts, HasCustomer, HasOrderStatus;

    public $fillable = ['extra_information', 'discount_percentage'];

    public static function boot()
    {
        parent::boot();

        self::creating(function(Model $model) {
            $model->order_token = Str::random(40);
            return $model;
        });

        self::saving(function(Model $model) {
            if ($model->discount_percentage > 0) {
                $model->total_value *= round($model->total_value * (1 - ($model->discount_percentage / 100)));
            }
        });
    }

}
