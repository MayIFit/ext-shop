<?php

namespace MayIFit\Extension\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

use MayIFit\Core\Permission\Traits\HasUsers;

use MayIFit\Extension\Shop\Models\Reseller;

class ResellerGroup extends Model
{
    use HasUsers, Notifiable;

    protected $fillable = [
        'name',
        'discount_value',
    ];
    
    public static function boot() {
        parent::boot();
        self::creating(function($model) {
            $model->createdBy()->associate(Auth::user());
            return $model;
        });

        self::updating(function($model) {
            $model->updatedBy()->associate(Auth::user());
            return $model;
        });
    }

    public function resellers(): HasMany {
        return $this->hasMany(Reseller::class);
    }
}
