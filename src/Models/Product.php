<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;

use MayIFit\Core\Permission\Traits\HasUers;
use MayIFit\Extension\Shop\Traits\HasCategories;

class Product extends Model
{
    use HasCategories, HasUers;

    protected $casts = [
        'technical_specs' => 'array',
    ];


    public function parentProduct() {
        return $this->belongsTo(Product::class, 'parent_product_id', 'id');
    }

    public function accessories() {
        return $this->hasMany(Product::class, 'parent_product_id', 'id');
    }

    protected function asJson($value) {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
