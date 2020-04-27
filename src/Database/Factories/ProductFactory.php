<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use MayIFit\Extension\Shop\Models\Product;

$factory->define(Product::class, function (Faker $faker) {

    $technicalSpecs = array(
        'weight' => $faker->numberBetween($min = 0, $max = 820).'kg',
        'width' => $faker->numberBetween($min = 0, $max = 1870),
        'height' => $faker->numberBetween($min = 0, $max = 920),
        'length' => $faker->numberBetween($min = 0, $max = 1230),
    );

    return [
        'catalog_id' => $faker->numerify('prdct_########'),
        'name' => $faker->text($maxNbChars = 30),
        'description' => $faker->text($maxNbChars = 200),
        'net_price' => $faker->numberBetween($min = 1000, $max = 40000),
        'vat' => $faker->numberBetween($min = 0, $max = 27),
        'in_stock' => $faker->numberBetween($min = 0, $max = 73),
        'technical_specs' => $technicalSpecs,
        'discount_percentage' => $faker->numberBetween($min = 5, $max = 85),
        'parent_product_id' => ($faker->numberBetween($min = 1, $max = 100)) > 50 ? $faker->numberBetween($min = 1, $max = 10) : null
    ];
});
