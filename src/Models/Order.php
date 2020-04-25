<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;

use MayIFit\Extension\Shop\Traits\HasProducts;
use MayIFit\Extension\Shop\Traits\HasCustomer;

class Order extends Model
{
    use HasProducts, HasCustomer;
    
}
