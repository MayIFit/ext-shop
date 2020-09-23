<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\ProductPricing;

$factory->define(Product::class, function (Faker $faker) {

    $technicalSpecs = array(
        'weight' => $faker->numberBetween(0, 500) . ' kg',
        'width' => $faker->numberBetween(0, 1870) . ' mm',
        'height' => $faker->numberBetween(0, 920) . ' mm',
        'length' => $faker->numberBetween(0, 1230) . ' mm',
    );

    return [
        'catalog_id' => $faker->numerify('prdct_########'),
        'name' => $faker->text(30),
        'description' => $faker->text(500),
        'stock' => $faker->numberBetween(0, 73),
        'technical_specs' => $technicalSpecs,
        'varranty' => '1 year',
        'supplied' => [
            'accumulator' => '24v/2A',
            'manual' => '',
            'bag' => '25l',
        ]
    ];
});

$factory->afterCreating(Product::class, function ($product) {
    $product->pricings()->save(factory(ProductPricing::class)->make());
});
