<?php

namespace MayIFit\Extension\Shop\Tests\Feature;

use Illuminate\Foundation\Auth\User as BaseUser;
use Laravel\Sanctum\Sanctum;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;

class CanCreateProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product(): void {
        parent::setUp();
        
        $user = new User;
        $user->id = 1;
        Sanctum::actingAs($user);
    
        $resp = $this->graphQL("mutation { 
            createProduct(input: {
                catalog_id: \"20001\",
                refurbrished: FALSE,
                varranty: \"1 year\"
            }) {
                catalog_id
            }
        }");
        dd($resp);
        $resp->assertJSON([
            'data' => [
                'createProduct' => [
                    'catalog_id' => 20001
                ]
            ]

        ]);
    }
}

class User extends BaseUser
{
    use HasApiTokens;
}
