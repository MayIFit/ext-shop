<?php

namespace MayIFit\Extension\Shop\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;

class CanCreateProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product(): void
    {
        parent::setUp();

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
