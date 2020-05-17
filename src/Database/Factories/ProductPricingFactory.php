<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use MayIFit\Extension\Shop\Models\ProductPricing;
use MayIFit\Extension\Shop\Models\Product;

$factory->define(ProductPricing::class, function (Faker $faker) {
    $product = Product::all()->random();
    $netPrice = $faker->numberBetween(100, 1000000);
    $vat = $faker->numberBetween(1, 27);
    return [
        'product_id' => $product->catalog_id,
        'net_price' => $netPrice,
        'vat' => $vat,
        'gross_price' => $netPrice * (1 + ($vat / 100)),
        'currency' => 'HUF'
    ];
});
