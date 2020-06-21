<?php

namespace MayIFit\Extension\Shop\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use MayIFit\Core\Permission\Traits\HasUsers;
use MayIFit\Extension\Shop\Traits\HasReseller;

class ProductCategoryDiscount extends Model
{
    use SoftDeletes, HasUsers, HasProduct, HasReseller;

    public $fillable = [
        'product_cagtegory_id',
        'reseller_id',
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
