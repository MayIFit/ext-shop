<?php

namespace MayIFit\Extension\Shop\Tests\Unit\ResellerGroup;

use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

use MayIFit\Extension\Shop\Tests\TestCase;
use MayIFit\Extension\Shop\Models\Reseller;
use MayIFit\Extension\Shop\Models\ResellerGroup;
use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductPricing;

use MayIFit\Extension\Shop\Tests\User;

class CalculateResellerGroupDiscountTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_calculate_reseller_group_discount(): void
    {
        parent::setUp();

        $user = factory(User::class)->create();
        Sanctum::actingAs($user, ['*']);

        $resellerGroup = new ResellerGroup();
        $resellerGroup->name = 'test';
        $resellerGroup->discount_value = 10.0;
        $resellerGroup->save();

        $reseller = factory(Reseller::class)->create([
            'user_id' => $user->id,
            'reseller_group_id' => $resellerGroup->id
        ]);

        $product = factory(Product::class)->create();
        $pricing = factory(ProductPricing::class)->create([
            'product_id' => $product->id,
            'base_price' => 100.00,
            'vat' => 27,
            'reseller_id' => $reseller->id
        ]);

        $resellerGroupDiscountPrice = $pricing->getBaseGrossPriceAttribute() * (1 - ($reseller->resellerGroup->discount_value / 100));

        $this->assertEquals(114.3, $resellerGroupDiscountPrice);
    }
}
