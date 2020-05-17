<?php

namespace MayIFit\Extension\Shop\Database\Seeds;

use Illuminate\Database\Seeder;

use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductCategories;

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
            $net_price = $faker->numberBetween(100, 1000000);
            $vat = $faker->numberBetween(1, 27);
            $product->category()->assoicate(ProductCategories::all()->random());
            $product->pricing()->create([
                'product_id' => $product->catalog_id,
                'net_price' => $net_price,
                'vat' => $vat,
                'gross_price' => $net_price * (1 + ($vat / 100)),
                'currency' => 'HUF'
            ]);
            if (rand(1, 100) > 85) {
                $product->parentProduct()->associate(Product::all()->random());
            }
            $product->save();
        });
    }
}
