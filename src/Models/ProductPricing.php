<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

use MayIFit\Core\Permission\Traits\HasUsers;
use MayIFit\Extension\Shop\Traits\HasProduct;


class ProductPricing extends Model
{
    use SoftDeletes, HasProduct, HasUsers;

    protected $with = ['user'];

    public $fillable = [
        'product_id',
        'user_id',
        'base_price',
        'vat',
        'currency'
    ];

    protected $attributes = [
        'base_price' => 0.00,
        'vat' => 0.00,
        'currency' => 'HUF',
        'quantity_based' => false
    ];

    public static function booted() {
        self::created(function(Model $model) {
            $model->available_from = Carbon::now();
        });
    }

    public function getGrossPriceAttribute(): float {
        return $this->base_price * (1 + ($this->vat / 100));
    }
}
