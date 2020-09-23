<?php

namespace MayIFit\Extension\Shop\Tests\Feature;

use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;

use App\Models\User;

class CanUpdateProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update(): void
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
        ');

        $this->graphQL('
            mutation {
                updateProduct(input: {
                    id: 1
                    catalog_id: "22222"
                }) {
                    catalog_id
                }
            }
        ')->assertJSON([
            'data' => [
                'updateProduct' => [
                    'catalog_id' => 22222
                ]
            ]
        ]);
    }
}
