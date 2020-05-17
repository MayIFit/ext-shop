<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;

use MayIFit\Extension\Shop\Traits\HasProducts;

class ProductCategory extends Model
{
    use HasProducts;

    public $fillable = [
        'name',
        'description',
        'parent_id'
    ];
}
