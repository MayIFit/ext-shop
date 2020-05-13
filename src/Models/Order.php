<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use MayIFit\Extension\Shop\Traits\HasProducts;
use MayIFit\Extension\Shop\Traits\HasCustomer;
use MayIFit\Extension\Shop\Traits\HasStatus;

class Order extends Model
{
    use HasProducts, HasCustomer, HasStatus;

    public $fillable = ["extra_information"];

    public static function boot()
    {
        parent::boot();

        self::creating(function(Model $model) {
            $model->order_token = Str::random(40);
            return $model;
        });
    }

}
