<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

use MayIFit\Core\Permission\Traits\HasUsers;

use MayIFit\Extension\Shop\Models\ResellerGroup;

class Reseller extends Model
{
    use HasUsers, Notifiable;

    protected $fillable = [
        'phone_number',
        'email',
        'vat_id',
        'company_name',
        'contact_person',
        'supplier_customer_code',
        'user_id',
        'reseller_group_id',
    ];
    

    public static function boot() {
        parent::boot();
        self::creating(function($model) {
            if (!$model->user) {
                $model->user()->associate(Auth::user());
            }
        });
    }

    public function resellerGroup(): BelongsTo {
        return $this->belongsTo(ResellerGroup::class);
    }
}
