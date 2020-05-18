<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

use MayIFit\Extension\Shop\Models\ProductPricing;
use MayIFit\Extension\Shop\Models\ProductDiscount;
use MayIFit\Extension\Shop\Models\ProductCategory;

use MayIFit\Core\Permission\Traits\HasUsers;
use MayIFit\Core\Permission\Traits\HasDocuments;
use MayIFit\Extension\Shop\Traits\HasOrders;

class Product extends Model
{
    use HasUsers, HasOrders, HasDocuments;

    protected $guarded = [];
    protected $with = ['pricing', 'category', 'discount'];
    protected $casts = [
        'technical_specs' => 'array',
    ];

    protected $attributes = [
        'in_stock' => 0
    ];

    protected $primaryKey = 'catalog_id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function getGrossPriceAttribute(): float {
        return $this->pricing()->net_price * (1 + ($this->pricing()->vat / 100));
    }

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

    public function pricing(): HasOne {
        return $this->hasOne(ProductPricing::class);
    }

    public function discount(): HasOne {
        return $this->hasOne(ProductDiscount::class);
    }
}
