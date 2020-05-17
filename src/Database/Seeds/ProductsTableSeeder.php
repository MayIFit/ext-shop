<?php

namespace MayIFit\Extension\Shop\Database\Seeds;

use Illuminate\Database\Seeder;

use MayIFit\Extension\Shop\Models\Product;

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
            if (rand(1, 100) > 85) {
                $product->parentProduct()->associate(Product::all()->random());
            }
            $product->save();
        });
    }
}
