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

    protected $attributes = [
        'net_price' => 0.00,
        'vat' => 0.00,
        'currency' => 'HUF'
    ];
}
