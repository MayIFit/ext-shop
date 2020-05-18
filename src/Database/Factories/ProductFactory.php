<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use MayIFit\Extension\Shop\Models\Product;

$factory->define(Product::class, function (Faker $faker) {

    $technicalSpecs = array(
        'weight' => $faker->numberBetween($min = 0, $max = 500).' kg',
        'width' => $faker->numberBetween($min = 0, $max = 1870),
        'height' => $faker->numberBetween($min = 0, $max = 920),
        'length' => $faker->numberBetween($min = 0, $max = 1230),
    );

    return [
        'catalog_id' => $faker->numerify('prdct_########'),
        'name' => $faker->text($maxNbChars = 30),
        'description' => $faker->text($maxNbChars = 2000),
        'in_stock' => $faker->numberBetween($min = 0, $max = 73),
        'technical_specs' => $technicalSpecs,
    ];
});
