<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use MayIFit\Core\Permission\Traits\HasCreators;
use MayIFit\Extension\Shop\Traits\HasReseller;
use MayIFit\Extension\Shop\Traits\HasProduct;

/**
 * Class ProductPricing
 *
 * @package MayIFit\Extension\Shop
 */
class ProductPricing extends Model
{
    use SoftDeletes;
    use HasCreators;
    use HasProduct;
    use HasReseller;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'product_id',
        'reseller_id',
        'base_price',
        'vat',
        'currency',
        'wholesale_price',
        'available_from',
        'is_discounted'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'available_from' => 'datetime:Y-m-d h:i:s'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'base_price' => 0.00,
        'wholesale_price' => 0.00,
        'vat' => 0.00,
        'currency' => 'HUF',
        'is_discounted' => false
    ];

    public function getBaseGrossPriceAttribute(): float
    {
        return round($this->base_price * (1 + ($this->vat / 100)), 2, PHP_ROUND_HALF_EVEN);
    }

    public function getWholeSaleGrossPriceAttribute(): float
    {
        return round($this->wholesale_price * (1 + ($this->vat / 100)), 2, PHP_ROUND_HALF_EVEN);
    }
}
