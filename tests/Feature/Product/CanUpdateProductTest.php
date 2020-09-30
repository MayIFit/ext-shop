<?php

namespace MayIFit\Extension\Shop\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;

class CanUpdateProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_product(): void
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
