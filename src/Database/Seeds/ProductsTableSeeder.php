<?php

namespace MayIFit\Extension\Shop\Database\Seeds;

use Illuminate\Database\Seeder;

use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductCategory;
use MayIFit\Extension\Shop\Models\ProductPricing;
use MayIFit\Extension\Shop\Models\ProductDiscount;

/**
 * Class ProductsTableSeeder
 *
 * @package MayIFit\Extension\Shop
 */
class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Product::class, 1000)->make()
            ->each(function($product) {
                $product->createdBy()->associate(1);
                $product->category()->associate(ProductCategory::all()->random());
                if (rand(1, 100) > 85) {
                    $product->parentProduct()->associate(Product::all()->random());
                }
                $pricing = factory(ProductPricing::class)->create([
                    'product_id' => $product->id
                ]);
                $eurPricing = factory(ProductPricing::class)->create([
                    'product_id' => $product->id,
                    'currency' => 'EUR'
                ]);
                $discount = factory(ProductDiscount::class)->create([
                    'product_id' => $product->id
                ]);
                $discount->product()->associate($product);
                $pricing->product()->associate($product);
                $eurPricing->product()->associate($product);
                $product->save();
            });
    }
}
