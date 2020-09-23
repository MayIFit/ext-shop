<?php

namespace MayIFit\Extension\Shop\Tests\Unit\Product;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;
use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductDiscount;

class GetProductCurrentDiscountTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_product_current_discount(): void
    {
        parent::setUp();

        $product = factory(Product::class)->create();
        factory(ProductDiscount::class)->create([
            'product_id' => $product->id
        ]);

        // Discount for the future
        $discount = factory(ProductDiscount::class)->create([
            'product_id' => $product->id,
            'available_from' => Carbon::now()->addDays(30)
        ]);


        $this->assertNotEquals($product->getDiscountForDate(null, ['dateTime' => Carbon::now()], null, null)->id, $discount->id);
    }
}
