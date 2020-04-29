<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;

use MayIFit\Extension\Shop\Models\Product;

class Document extends Model
{
    protected $table = 'uploaded_documents';

    /**
     * Get all of the products that have this document.
     */
    public function products()
    {
        return $this->morphedByMany(Product::class, 'entity');
    }
}
