<?php

namespace MayIFit\Extension\Shop\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use MayIFit\Extension\Shop\Models\Customer;
use MayIFit\Extension\Shop\Models\Pivots\CustomerOrderPivot;

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function customers(): BelongsToMany {
        return $this->belongsToMany(Customer::class);
    }


}