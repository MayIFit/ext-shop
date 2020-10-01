<?php

namespace MayIFit\Extension\Shop\Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;
use MayIFit\Extension\Shop\Models\Product;

class CanUpdateProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_product(): void
    {
        parent::setUp();

        $product = factory(Product::class)->create();

        $this->graphQL('
            mutation {
                updateProduct(input: {
                    id: ' . $product->id . '
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
