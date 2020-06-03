<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use MayIFit\Extension\Shop\Models\ProductReview;

$factory->define(ProductReview::class, function (Faker $faker) {
    return [
        'rating' => rand(1, 5),
        'message' => $faker->text,
    ];
});
