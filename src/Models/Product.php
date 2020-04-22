<?php

namespace MayIFit\Core\Translation\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $table = "products";

    protected function asJson($value) {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
