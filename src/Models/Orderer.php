<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;

use MayIFit\Extension\Shop\Traits\HasOrders;
use MayIFit\Extension\Shop\Traits\HasUser;

class Orderer extends Model
{
    use HasOrders, HasUser;
    
}
