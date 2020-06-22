<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
        'user_id',
        'base_price',
        'vat',
        'currency'
    ];

    protected $attributes = [
        'base_price' => 0.00,
        'wholesale_price' => 0.00,
        'vat' => 0.00,
        'currency' => 'HUF'
    ];

    public static function booted() {
        self::created(function(Model $model) {
            $model->available_from = Carbon::now();
        });
    }

    public function getGrossPriceAttribute(): float {
        return $this->base_price * (1 + ($this->vat / 100));
    }

    public function resellerPricing($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Builder {
        return DB::table('product_pricings')
            ->join('products', 'product_pricings.product_id', '=', 'products.id')
            ->where('reseller_id', '=', $args['reseller_id']);
    }
}
