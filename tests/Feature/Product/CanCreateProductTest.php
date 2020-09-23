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

        $user = factory(User::class)->create();
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
