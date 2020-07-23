<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Carbon\Carbon;

use MayIFit\Core\Permission\Traits\HasUsers;
use MayIFit\Extension\Shop\Traits\HasReseller;
use MayIFit\Extension\Shop\Traits\HasProduct;


class ProductPricing extends Model
{
    use SoftDeletes, HasUsers, HasProduct, HasReseller;

    protected $with = ['user'];

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

    public static function booted() {
        self::creating(function(Model $model) {
            if (!$model->available_from) {
                $model->available_from = Carbon::now();
            }
            if (!$model->wholesale_price) {
                $model->wholesale_price = $model->base_price;
            }

            $model->createdBy()->associate(Auth::user());

            return $model;
        });

        self::updating(function($model) {
            $model->updatedBy()->associate(Auth::user());
            return $model;
        });
    }

    public function getBaseGrossPriceAttribute(): float {
        return $this->base_price * (1 + ($this->vat / 100));
    }

    public function getWholeSaleGrossPriceAttribute(): float {
        return $this->wholesale_price * (1 + ($this->vat / 100));
    }
}
