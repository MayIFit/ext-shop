<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use MayIFit\Extension\Shop\Traits\HasProducts;
use MayIFit\Extension\Shop\Traits\HasCustomer;

class Order extends Model
{
    use HasProducts, HasCustomer;

    protected $fillable = ['sync'];

    public static function boot()
    {
        parent::boot();

        self::creating(function($model) {
            $model->order_token = Str::random(40);
        });
    }

}
