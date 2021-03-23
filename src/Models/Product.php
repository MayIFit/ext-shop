<?php

namespace MayIFit\Extension\Shop\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Nuwave\Lighthouse\Schema\Context as GraphQLContext;
use GraphQL\Type\Definition\ResolveInfo;

use MayIFit\Core\Permission\Traits\HasCreators;
use MayIFit\Core\Permission\Traits\HasDocuments;

use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;
use MayIFit\Extension\Shop\Models\ProductPricing;
use MayIFit\Extension\Shop\Models\ProductDiscount;
use MayIFit\Extension\Shop\Models\ProductCategory;
use MayIFit\Extension\Shop\Traits\HasOrders;
use MayIFit\Extension\Shop\Traits\HasReviews;

/**
 * Class Product
 *
 * @package MayIFit\Extension\Shop
 */
class Product extends Model
{
    use SoftDeletes;
    use HasCreators;
    use HasOrders;
    use HasReviews;
    use HasDocuments;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'technical_specs' => 'array',
        'supplied' => 'array'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'name' => '',
        'stock' => 0,
        'calculated_stock' => 0,
        'waste_stock' => 0,
        'technical_specs' => '{"":""}',
        'supplied' => '{"":""}',
        'varranty' => '1 year',
        'refurbished' => false,
        'orderable' => true,
        'out_of_stock_text' => '',
        'heading' => ''
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function accessories(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_accessory', 'product_id', 'accesory_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function pricings(): HasMany
    {
        return $this->hasMany(ProductPricing::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(ProductDiscount::class);
    }

    public function getCalculatedStockAttribute()
    {
        return $this->stock - OrderProductPivot::where([
            ['product_id', '=', $this->id],
            ['declined', false]
        ])->whereHas('order', function ($query) {
            return $query->whereNull('sent_to_courier_service');
        })->whereNull('shipped_at')->sum('quantity');
    }

    public function getCurrentPricing($rootValue = null, array $args = [], GraphQLContext $context = null, ResolveInfo $resolveInfo = null): ?ProductPricing
    {
        $reseller = $context->user->reseller->id ?? null;
        return $this->hasOne(ProductPricing::class)
            ->where(function ($query) use ($reseller) {
                return $query->where('reseller_id', $reseller)
                    ->orWhereNull('reseller_id');
            })
            ->where([['available_from', '<=', Carbon::now()]])
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function getDiscountForDate($rootValue, array $args, ?GraphQLContext $context, ?ResolveInfo $resolveInfo): ?ProductDiscount
    {
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
