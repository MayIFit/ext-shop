<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use MayIFit\Core\Permission\Traits\HasDocuments;
use MayIFit\Extension\Shop\Models\Product;

class ProductCategory extends Model
{
    use HasDocuments;
    
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
}
