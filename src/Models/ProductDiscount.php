<?php

namespace MayIFit\Extension\Shop\Models;

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
}
