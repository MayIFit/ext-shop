<?php

namespace MayIFit\Extension\Shop\Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;
use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductPricing;

class CalculateGrossPriceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_calculate_gross_price(): void {
        parent::setUp();

        $product = factory(Product::class)->create();
        $pricing = factory(ProductPricing::class)->create([
            'product_id' => $product->id,
            'base_price' => 100.00,
            'vat' => 27,
        ]);
        
        $grossPrice = $pricing->getBaseGrossPriceAttribute();

        $this->assertEquals(127.00, $grossPrice);
    }
}