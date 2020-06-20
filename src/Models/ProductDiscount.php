<?php

namespace MayIFit\Extension\Shop\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use MayIFit\Core\Permission\Traits\HasUsers;
use MayIFit\Extension\Shop\Traits\HasCustomer;
use MayIFit\Extension\Shop\Traits\HasProduct;

class ProductDiscount extends Model
{
    use SoftDeletes, HasUsers, HasProduct, HasCustomer;

    public $fillable = [
        'product_id',
        'customer_id',
        'discount_percentage',
        'available_from',
        'available_to'
    ];

    protected $attributes = [
        'discount_percentage' => 0.00,
        'quantity_based' => false
    ];

    protected static function booted() {
        static::creating(function ($model) {
            $model->available_from = Carbon::now();
            return $model;
        });
    }
}
