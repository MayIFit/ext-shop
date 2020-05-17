<?php

namespace MayIFit\Extension\Shop\Database\Seeds;

use Illuminate\Database\Seeder;

use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductCategories;
use MayIFit\Extension\Shop\Models\ProductPricing;

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
        factory(Product::class, 10)->make()
        ->each(function($product) {
            $product->createdBy()->associate(1);
            $product->category()->associate(ProductCategories::all()->random());
            $product->pricing()->create(factory(ProductPricing::class)->create([
                'product_id' => $product->catalog_id
            ]));
            if (rand(1, 100) > 85) {
                $product->parentProduct()->associate(Product::all()->random());
            }
            $product->save();
        });
    }
}
