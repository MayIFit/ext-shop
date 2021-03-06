<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use MayIFit\Core\Permission\Traits\HasCreators;
use MayIFit\Extension\Shop\Traits\HasReseller;
use MayIFit\Extension\Shop\Traits\HasProduct;

/**
 * Class ProductDiscount
 *
 * @package MayIFit\Extension\Shop
 */
class ProductDiscount extends Model
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
        'discount_percentage',
        'available_from',
        'available_to'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'available_from' => 'datetime:Y-m-d h:i:s',
        'available_to' => 'datetime:Y-m-d h:i:s',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'discount_percentage' => 0.00,
        'quantity_based' => false
    ];
}
