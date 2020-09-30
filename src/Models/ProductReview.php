<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use MayIFit\Core\Permission\Traits\HasCreators;
use MayIFit\Extension\Shop\Traits\HasProduct;

/**
 * Class ProductReview
 *
 * @package MayIFit\Extension\Shop
 */
class ProductReview extends Model
{
    use SoftDeletes;
    use HasProduct;
    use HasCreators;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'message',
        'rating'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'rating' => 0
    ];
}
