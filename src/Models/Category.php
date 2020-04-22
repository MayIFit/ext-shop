<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;

use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Traits\HasProducts;

class Category extends Model
{
    use HasProducts;
   
}
