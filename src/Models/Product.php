<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Nuwave\Lighthouse\Schema\Context as GraphQLContext;
use GraphQL\Type\Definition\ResolveInfo;

use MayIFit\Core\Permission\Traits\HasUsers;
use MayIFit\Core\Permission\Traits\HasDocuments;

use MayIFit\Extension\Shop\Models\ProductPricing;
use MayIFit\Extension\Shop\Models\ProductDiscount;
use MayIFit\Extension\Shop\Models\ProductCategory;
use MayIFit\Extension\Shop\Traits\HasOrders;
use MayIFit\Extension\Shop\Traits\HasReviews;

class Product extends Model
{
    use SoftDeletes, HasUsers, HasOrders, HasReviews, HasDocuments;

    protected $guarded = [];
    protected $with = ['pricings', 'category', 'discounts', 'reviews'];
    protected $casts = [
        'technical_specs' => 'array',
        'supplied' => 'array'
    ];

    protected $attributes = [
        'in_stock' => 1,
        'waste_stock' => 0,
        'technical_specs' => '{"":""}',
        'supplied' => '{"":""}',
        'varranty' => '1 year',
        'refurbished' => false
    ];

    protected function asJson($value) {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function parentProduct(): BelongsTo {
        return $this->belongsTo(Product::class, 'parent_id', 'id');
    }

    public function accessories(): HasMany {
        return $this->hasMany(Product::class, 'parent_id', 'id');
    }

    public function category(): BelongsTo {
        return $this->belongsTo(ProductCategory::class);
    }

    public function pricings(): HasMany {
        return $this->hasMany(ProductPricing::class);
    }

    public function discounts(): HasMany {
        return $this->hasMany(ProductDiscount::class);
    }

    public function getPricingForReseller($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ?ProductPricing {
        return $this->hasOne(ProductPricing::class)
        ->when(isset($args['reseller_id']), function($query) use($args) {
            return $query->where(function($query) use($args) {
                return $query->where('reseller_id', '=', $args['reseller_id'])
                    ->orWhereNull('reseller_id');
            });
        })
        ->orderBy('id', 'DESC')->first();
    }

    public function getDiscountForDate($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ?ProductDiscount {
        return $this->hasOne(ProductDiscount::class)
            ->where(function ($query) use ($args) {
                $query->where('available_from', '<=', $args['dateTime']);
                $query->where('available_to', '>=', $args['dateTime']);
            })
            ->orWhere(function ($query) use ($args) {
                $query->where('available_from', '<=', $args['dateTime']);
                $query->whereNull('available_to');
            })
            ->first();
    }
}