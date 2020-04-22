<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use MayIFit\Extension\Shop\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {

    $technicalSpecs = array(
        'weight' => $faker->numberBetween($min = 0, $max = 820).'kg',
        'width' => $faker->numberBetween($min = 0, $max = 1870),
        'height' => $faker->numberBetween($min = 0, $max = 920),
        'length' => $faker->numberBetween($min = 0, $max = 1230),
    );

    return [
        'catalog_id' => $faker->numerify('prdct_####'),
        'name' => $faker->text($maxNbChars = 20),
        'description' => $faker->text($maxNbChars = 200),
        'price' => $faker->numberBetween($min = 1000, $max = 40000),
        'in_stock' => $faker->numberBetween($min = 0, $max = 73),
        'technical_specs' => $technicalSpecs,
        'parent_product_id' => $faker->numberBetween($min = 1, $max = 10)
    ];
});
