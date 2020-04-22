<?php

namespace MayIFit\Ext\Shop\Models;

use Illuminate\Database\Eloquent\Model;

use MayIFit\Ext\Shop\Models\Product;
use MayIFit\Ext\Shop\Traits\HasProducts;

class Category extends Model
{
    use HasProducts;
   
}
