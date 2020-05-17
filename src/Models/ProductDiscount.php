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

    protected $attributes = [
        'discount_percentage' => 0.00,
    ];

    protected static function booted() {
        static::creating(function ($model) {
            $model->available_from = Carbon::now();
            return $model;
        });
    }
}
