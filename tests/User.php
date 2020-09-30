<?php

namespace MayIFit\Extension\Shop\Tests;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Laravel\Sanctum\HasApiTokens;

use MayIFit\Core\Permission\Traits\HasPermissions;

use MayIFit\Core\Permission\Models\SystemSetting;
use MayIFit\Extension\Shop\Models\Reseller;
use MayIFit\Extension\Shop\Traits\HasProduct;
use MayIFit\Extension\Shop\Traits\HasReseller;

class User extends Model implements AuthorizableContract, AuthenticatableContract
{
    use HasApiTokens;
    use Authorizable;
    use Authenticatable;
    use HasReseller;
    use HasProduct;
    use HasPermissions;

    protected $guarded = [];

    protected $table = 'users';

    protected $morphClass = 'user';

    public function createdSystemSettings()
    {
        return $this->morphMany(SystemSetting::class, 'createdBy');
    }

    public function reseller()
    {
        return $this->morphMany(Reseller::class, 'reseller');
    }
}
