<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;

use MayIFit\Core\Permission\Traits\HasProducts;

class Order extends Model
{
    use HasProducts;
    
}
