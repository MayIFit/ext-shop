<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Nuwave\Lighthouse\Schema\Context as GraphQLContext;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;

use MayIFit\Core\Permission\Traits\HasCreators;
use MayIFit\Core\Permission\Traits\HasDocuments;

use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductCategoryDiscount;

class ProductCategory extends Model
{
    use SoftDeletes;
    use HasCreators;
    use HasDocuments;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'name',
        'description',
        'parent_id'
    ];

    protected $with = [
        'children',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    public function categoryRecursive()
    {
        return $this->children()->with('categoryRecursive');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id')->where('orderable', 1);
    }

    public function getProductCountAttribute(): int
    {
        return $this->products()->count();
    }

    public function getDiscountForDate($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ?ProductCategoryDiscount
    {
        return $this->hasOne(ProductCategoryDiscount::class)
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
