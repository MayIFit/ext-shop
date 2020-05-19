<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;

use MayIFit\Extension\Shop\Traits\HasProduct;

class ProductPricing extends Model
{
    use HasProduct;

    public $fillable = [
        'product_catalog_id',
        'base_price',
        'vat',
        'currency'
    ];

    protected $attributes = [
        'base_price' => 0.00,
        'vat' => 0.00,
        'currency' => 'HUF'
    ];

    public function getGrossPriceAttribute(): float {
        return $this->base_price * (1 + ($this->vat / 100));
    }
}
