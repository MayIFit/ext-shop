<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use MayIFit\Extension\Shop\Models\ProductPricing;

$factory->define(ProductPricing::class, function (Faker $faker) {
    $net_price = $faker->numberBetween(100, 1000000);
    $vat = $faker->numberBetween(1, 27);
    return [
        'net_price' => $net_price,
        'vat' => $vat,
        'gross_price' => $net_price * (1 + ($vat / 100)),
        'currency' => 'HUF'
    ];
});
