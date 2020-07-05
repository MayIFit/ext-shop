<?php

namespace MayIFit\Extension\Shop\Tests\Feature;

use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;

use App\Models\User;

class CanCreateProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product(): void {
        parent::setUp();
        
        $user = new User;
        $user->id = 1;
        Sanctum::actingAs($user);
    
        $resp = $this->graphQL('
            mutation {
                createProduct(input: {
                    catalog_id: "20001"
                    name:"asd"
                    refurbished: false
                    varranty: "1 year"
                }) {
                    catalog_id
                }
            }
        ');
        $resp->assertJSON([
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

class User extends BaseUser
{
    use HasApiTokens, HasPermissions;
}
