<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use MayIFit\Extension\Shop\Models\Order;
use MayIFit\Extension\Shop\Models\Customer;
use MayIFit\Extension\Shop\Models\Pivots\OrderProductPivot;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'order_status_id' => $faker->numberBetween(1, 6),
        'order_id_prefix' => 'ORD',
        'extra_information' => 'Test',
        'payment_type' => 1,
        'delivery_type' => 10
    ];
});

$factory->state(Order::class, 'shippable', [
    'order_status_id' => 1
]);

$factory->afterCreatingState(Order::class, 'un_shippable', function ($order) {
    factory(OrderProductPivot::class, 5)->states('un_shippable')
        ->create()
        ->each(function ($pivot) use (&$order) {
            $exclude = [$pivot->getForeignKey(), 'id'];
            $extra_attributes = array_except($pivot->getAttributes(), $exclude);
            $order->products()->attach($pivot, $extra_attributes);
        });

    $order->shippingAddress()->associate(factory(Customer::class)->make());
});

$factory->afterCreatingState(Order::class, 'shippable', function ($order) {
    factory(OrderProductPivot::class, 5)->states('shippable')
        ->create()
        ->each(function ($pivot) use (&$order) {
            $exclude = [$pivot->getForeignKey(), 'id'];
            $extra_attributes = array_except($pivot->getAttributes(), $exclude);
            $order->products()->attach($pivot, $extra_attributes);
        });

    $order->shippingAddress()->associate(factory(Customer::class)->make());
});

$factory->afterCreatingState(Order::class, 'partially_shippable', function ($order) {
    factory(OrderProductPivot::class, 5)->states('shippable')
        ->create()
        ->each(function ($pivot) use (&$order) {
            $exclude = [$pivot->getForeignKey(), 'id'];
            $extra_attributes = array_except($pivot->getAttributes(), $exclude);
            $order->products()->attach($pivot, $extra_attributes);
        });

    factory(OrderProductPivot::class, 5)->states('un_shippable')
        ->create()
        ->each(function ($pivot) use (&$order) {
            $exclude = [$pivot->getForeignKey(), 'id'];
            $extra_attributes = array_except($pivot->getAttributes(), $exclude);
            $order->products()->attach($pivot, $extra_attributes);
        });

    $order->shippingAddress()->associate(factory(Customer::class)->make());
});
