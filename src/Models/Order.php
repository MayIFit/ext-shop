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
            return $model;
        });
    }

}
