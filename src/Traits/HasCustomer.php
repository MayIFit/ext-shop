<?php

namespace MayIFit\Extension\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use MayIFit\Extension\Shop\Models\Customer;

/**
 * Class HasOrderer
 *
 * @package MayIFit\Extension\Shop\Traits
 */
trait HasCustomer {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class);
    }
}