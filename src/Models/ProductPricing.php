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

    public function listResellerProductPricing($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Builder {
        return DB::table('product_pricings')
            ->select(
                'product_pricings.id', 
                'product_pricings.base_price',
                'product_pricings.wholesale_price',
                'product_pricings.vat',
                'product_pricings.reseller_id',
                'product_pricings.available_from',
                DB::raw('product_pricings.base_price * (1 + (product_pricings.vat / 100)) as base_gross_price'),
                DB::raw('product_pricings.wholesale_price * (1 + (product_pricings.vat / 100)) as wholesale_gross_price'),
                'product_pricings.currency',
                'product_pricings.reseller_id',
                'products.catalog_id',
                'products.name',
                'products.in_stock',
                'documents.resource_url',
            )
            ->join('products', 'product_pricings.product_id', '=', 'products.id')
            ->leftJoin('documents', function($join) {
                $join->on('documents.documentable_type', '=', 'product');
                $join->on('documents.documentable_id', '=', 'products.id');
            })
            ->where('reseller_id', '=', $args['reseller_id'])
            ->orWhereNull('reseller_id');
    }
}
