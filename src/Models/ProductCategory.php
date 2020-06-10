<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use MayIFit\Core\Permission\Traits\HasDocuments;
use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductCategoryDiscount;

class ProductCategory extends Model
{
    use SoftDeletes, HasDocuments;
    
    public $fillable = [
        'name',
        'description',
        'parent_id'
    ];

    public function parentCategory(): BelongsTo {
        return $this->belongsTo(ProductCategory::class, 'parent_id', 'id');
    }

    public function products(): HasMany {
        return $this->hasMany(Product::class);
    }

    public function getDiscountForDate($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ?ProductCategoryDiscount {
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
