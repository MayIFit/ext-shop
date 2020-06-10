<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use MayIFit\Extension\Shop\Traits\HasProduct;
use MayIFit\Extension\Shop\Traits\HasCustomer;


class ProductPricing extends Model
{
    use SoftDeletes, HasProduct, HasCustomer;

    protected $with = ['customer'];

    public $fillable = [
        'product_id',
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
