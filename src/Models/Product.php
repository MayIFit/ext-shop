<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;

use MayIFit\Core\Permission\Traits\HasUsers;
use MayIFit\Extension\Shop\Traits\HasCategories;
use MayIFit\Extension\Shop\Traits\HasOrders;

class Product extends Model
{
    use HasCategories, HasUsers, HasOrders;

    protected $casts = [
        'technical_specs' => 'array',
    ];

    protected $primaryKey = 'catalog_id';
    protected $keyType = 'string';
    public $incrementing = false;


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
