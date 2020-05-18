<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use MayIFit\Extension\Shop\Models\ProductPricing;

$factory->define(ProductPricing::class, function (Faker $faker) {
    $base_price = $faker->numberBetween(100, 100000);
    $vat = $faker->numberBetween(1, 27);
    return [
        'base_price' => round($base_price, -3),
        'vat' => $vat,
        'currency' => 'HUF'
    ];
});
