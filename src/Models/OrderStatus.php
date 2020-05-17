<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    public static function booted() {
        self::creating(function(Model $model) {
            $model->active = true;
            return $model;
        });
    }
}
