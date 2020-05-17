<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use MayIFit\Extension\Shop\Models\ProductDiscount;
use MayIFit\Extension\Shop\Models\Product;

$factory->define(ProductDiscount::class, function (Faker $faker) {
    $product = Product::all()->random();
    return [
        'product_id' => $product->catalog_id,
        'discount_percentage' => $faker->numberBetween(0, 80),
        'available_to' => $faker->dateTimeBetween('now', '+30 years')
    ];
});
