<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use MayIFit\Extension\Shop\Models\ProductCategory;

$factory->define(ProductCategory::class, function (Faker $faker) {
    $hasParent = $faker->boolean;
    return [
        'name' => $faker->text($maxNbChars = 30),
        'description' => $faker->bs,
        'parent_id' => $hasParent ? $faker->numberBetween(1, 6) : null
    ];
});
