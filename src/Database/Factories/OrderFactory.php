<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

use MayIFit\Extension\Shop\Models\Order;

$factory->define(Order::class, function (Faker $faker) {
    $statuses = [
        'placed',
        'in_review',
        'approved',
        'transferred_for_shipping',
        'in_delivery',
        'delivered'
    ];

    return [
        'order_token' =>  Str::random(40),
        'order_status' => $statuses[$faker->numberBetween(0, count($statuses) - 1)]
    ];
});
