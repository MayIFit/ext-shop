<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;

use MayIFit\Extension\Shop\Traits\HasProduct;

class ProductPricing extends Model
{
    use HasProduct;

    public $fillable = [
        'product_catalog_id',
        'net_price',
        'vat',
        'currency'
    ];

    protected static function booted() {
        static::creating(function ($model) {
            $model->net_price = 1.0;
            $model->vat = 0.0;
            $model->currency = 'HUF';
        });
    }
}
