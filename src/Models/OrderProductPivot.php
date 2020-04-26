<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class OrderProductPivot extends MorphPivot
{
    protected $table = 'order_product';

    public static function boot()
    {
        parent::boot();

        self::creating(function($model) {
            return $model;
        });
    }
}