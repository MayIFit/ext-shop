<?php

namespace MayIFit\Extension\Shop\Tests\Unit\Product;

use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;
use MayIFit\Extension\Shop\Tests\User;
use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductPricing;

class GetProductCurrentPricingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_product_current_pricing(): void
    {
        parent::setUp();

        $product = factory(Product::class)->create();
        factory(ProductPricing::class)->create([
            'product_id' => $product->id,
            'base_price' => 100.00,
            'vat' => 27,
            'available_from' => Carbon::now()->subDays(30)
        ]);

        $pricing = factory(ProductPricing::class)->create([
            'product_id' => $product->id,
            'base_price' => 100.00,
            'vat' => 27,
        ]);

        $this->assertEquals($product->getCurrentPricing()->id, $pricing->id);
    }
}
