<?php

namespace MayIFit\Extension\Shop\Database\Seeds;

use Illuminate\Database\Seeder;

use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\Customer;
use MayIFit\Extension\Shop\Models\Order;

/**
 * Class OrdersTableSeeder
 *
 * @package MayIFit\Extension\Shop
 */
class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Order::class, 20)->make()
        ->each(function($order) {
            $products = Product::all()->random(5);
            $randomCustomer = Customer::all()->random();
            $order->customer()->associate($randomCustomer->id);
            $order->save();
            foreach ($products as $product) {
                $data = array('quantity' => rand(1, 100));
                $order->products()->attach($product->catalog_id, $data);
            }
            $order->save();
        });
    }
}
