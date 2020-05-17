<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;

use MayIFit\Core\Permission\Models\ProductPricing;
use MayIFit\Core\Permission\Models\ProductDiscount;
use MayIFit\Core\Permission\Models\ProductCategory;

use MayIFit\Core\Permission\Traits\HasUsers;
use MayIFit\Core\Permission\Traits\HasDocuments;
use MayIFit\Extension\Shop\Traits\HasCategories;
use MayIFit\Extension\Shop\Traits\HasOrders;

class Product extends Model
{
    use HasCategories, HasUsers, HasOrders, HasDocuments;

    protected $guarded = [];
    protected $with = ['pricing', 'category', 'discount'];
    protected $casts = [
        'technical_specs' => 'array',
    ];

    protected $primaryKey = 'catalog_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted() {
        static::created(function ($model) {
            $model->pricing()->create();
            $model->discount()->create();
        });
    }

    public function save(array $options = array()) {
        $this->created_by = auth()->id() ?? 1;
        $this->updated_by = auth()->id();
        parent::save($options);
    }

    public function getGrossPrice() {
        return $this->pricing->net_price * (1 + ($this->pricing->vat / 100));
    }

    protected function asJson($value) {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function parentProduct() {
        return $this->belongsTo(Product::class, 'parent_product_id', 'id');
    }

    public function accessories() {
        return $this->hasMany(Product::class, 'parent_product_id', 'id');
    }

    public function pricing() {
        return $this->belongsTo(ProductPricing::class);
    }

    public function category() {
        return $this->hasMany(ProductCategory::class);
    }

    public function discount() {
        return $this->belongsTo(ProductPricing::class);
    }
    
}
