<?php

use Illuminate\Database\Seeder;

use MayIFit\Extension\Shop\Models\Product;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Product::class, 100)->make()
        ->each(function($product) {
            $product->createdBy()->associate(1);
            $product->save();
        });
    }
}
