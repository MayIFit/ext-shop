<?php

namespace MayIFit\Extensions\Shop\Models;

use MayIFit\Core\Permission\Models\Document as BaseDocument;
use MayIFit\Core\Permission\Models\Product;

class Document extends BaseDocument
{
    /**
     * Get all of the products that have this document.
     */
    public function products()
    {
        return $this->morphedByMany(Product::class, 'entity');
    }
}
