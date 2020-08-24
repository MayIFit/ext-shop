<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use MayIFit\Extension\Shop\Models\Order;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'order_status_id' => $faker->numberBetween(1, 6)
    ];
});
