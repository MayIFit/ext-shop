<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

use MayIFit\Core\Permission\Traits\HasUsers;
use MayIFit\Core\Permission\Traits\HasDocuments;

use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductCategoryDiscount;

class ProductCategory extends Model
{
    use SoftDeletes, HasUsers, HasDocuments;
    
    public $fillable = [
        'name',
        'description',
        'parent_id'
    ];

    public static function boot() {
        parent::boot();
        self::creating(function($model) {
            $model->createdBy()->associate(Auth::user());
            return $model;
        });

        self::updating(function($model) {
            $model->updatedBy()->associate(Auth::user());
            return $model;
        });
    }

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
