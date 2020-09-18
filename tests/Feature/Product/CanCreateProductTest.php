<?php

namespace MayIFit\Extension\Shop\Tests\Feature;

use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;

use App\Models\User;

class CanCreateProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product(): void
    {
        parent::setUp();

        $user = new User;
        $user->id = 1;
        Sanctum::actingAs($user, ['*']);

        $this->graphQL('
            mutation {
                createProduct(input: {
                    catalog_id: "20001"
                    name: "Test"
                    refurbished: false
                    varranty: "1 year"
                    orderable: true
                }) {
                    catalog_id
                }
            }
        ')->assertJSON([
            'data' => [
                'createProduct' => [
                    'catalog_id' => 20001
                ]
            ]
        ]);
    }
}

namespace App\Models;

use Illuminate\Foundation\Auth\User as BaseUser;
use Laravel\Sanctum\HasApiTokens;

use MayIFit\Core\Permission\Traits\HasPermissions;
use MayIFit\Extension\Shop\Traits\HasReseller;

class User extends BaseUser
{
    use HasApiTokens;
    use HasPermissions;
    use HasReseller;
}
