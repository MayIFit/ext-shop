<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use MayIFit\Extension\Shop\Models\Product;

$factory->define(Product::class, function (Faker $faker) {

    $technicalSpecs = array(
        'weight' => $faker->numberBetween($min = 0, $max = 500).' kg',
        'width' => $faker->numberBetween($min = 0, $max = 1870).' mm',
        'height' => $faker->numberBetween($min = 0, $max = 920).' mm',
        'length' => $faker->numberBetween($min = 0, $max = 1230).' mm',
    );

    return [
        'catalog_id' => $faker->numerify('prdct_########'),
        'name' => $faker->text($maxNbChars = 30),
        'description' => $faker->text($maxNbChars = 500),
        'in_stock' => $faker->numberBetween($min = 0, $max = 73),
        'technical_specs' => $technicalSpecs,
        'varranty' => '1 year',
        'supplied' => [
            'accumulator' => '24v/2A',
            'manual' => '',
            'bag' => '25l',
        ]
    ];
});
