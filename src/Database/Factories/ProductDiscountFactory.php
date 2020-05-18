<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use MayIFit\Extension\Shop\Models\ProductDiscount;
use MayIFit\Extension\Shop\Models\Product;

$factory->define(ProductDiscount::class, function (Faker $faker) {
    $hasDiscount = $faker->boolean($chanceOfGettingTrue = 15); 
    return [
        'discount_percentage' => $hasDiscount ? $faker->numberBetween(1, 80) : 0,
        'available_to' => $faker->dateTimeBetween('now', '+2 weeks')
    ];
});
