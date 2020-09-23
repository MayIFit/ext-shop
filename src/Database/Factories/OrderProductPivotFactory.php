<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;

$factory->define(OrderProductPivot::class, function (Faker $faker) {
    return [
        'quantity' => $faker->numberBetween(0, 50),
    ];
});

$factory->state(OrderProductPivot::class, 'shippable', [
    'quantity' => '1',
]);

$factory->state(OrderProductPivot::class, 'un_shippable', [
    'quantity' => '10000',
]);

$factory->afterMaking(OrderProductPivot::class, function ($pivot) {
    $product = factory(Product::class)->create();
    $pivot->product()->associate($product);
    $pivot->pricing()->associate($product->pricings()->first()->id);
});

$factory->afterMakingState(OrderProductPivot::class, 'shippable', function ($pivot) {
    $product = factory(Product::class)->create([
        'stock' => 10
    ]);
    $pivot->product()->associate($product);
    $pivot->pricing()->associate($product->pricings()->first()->id);
});
