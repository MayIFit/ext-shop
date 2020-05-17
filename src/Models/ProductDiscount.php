<?php

namespace MayIFit\Extension\Shop\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use MayIFit\Extension\Shop\Traits\HasProduct;

class ProductDiscount extends Model
{
    use HasProduct;

    public $fillable = [
        'product_catalog_id',
        'discount_percentage',
        'available_from',
        'available_to'
    ];

    protected static function booted() {
        static::created(function ($model) {
            $model->discount_percentage = 0.0;
            $model->vat = 0.0;
            $model->currency = 'HUF';
            $model->available_from = Carbon::now();
        });
    }
}
