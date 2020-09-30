<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use MayIFit\Core\Permission\Traits\HasCreators;
use MayIFit\Extension\Shop\Traits\HasReseller;
use MayIFit\Extension\Shop\Models\ProductCategory;

/**
 * Class ProductCategoryDiscount
 *
 * @package MayIFit\Extension\Shop
 */
class ProductCategoryDiscount extends Model
{
    use SoftDeletes;
    use HasCreators;
    use HasReseller;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'product_cagtegory_id',
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
    ];

    public function categories(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }
}
