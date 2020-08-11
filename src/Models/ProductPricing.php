<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use MayIFit\Core\Permission\Traits\HasUsers;
use MayIFit\Extension\Shop\Traits\HasReseller;
use MayIFit\Extension\Shop\Traits\HasProduct;

class ProductPricing extends Model
{
    use SoftDeletes, HasUsers, HasProduct, HasReseller;

    public $fillable = [
        'product_id',
        'reseller_id',
        'base_price',
        'vat',
        'currency',
        'wholesale_price',
        'available_from'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'available_from' => 'datetime:Y-m-d h:i:s'
    ];

    protected $attributes = [
        'base_price' => 0.00,
        'wholesale_price' => 0.00,
        'vat' => 0.00,
        'currency' => 'HUF'
    ];

    public function getBaseGrossPriceAttribute(): float {
        return round($this->base_price * (1 + ($this->vat / 100)));
    }

    public function getWholeSaleGrossPriceAttribute(): float {
        return round($this->wholesale_price * (1 + ($this->vat / 100)));
    }
}
