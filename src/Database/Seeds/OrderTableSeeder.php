<?php

namespace MayIFit\Extension\Shop\Database\Seeds;

use Illuminate\Database\Seeder;

use MayIFit\Extension\Shop\Models\Product;
use MayIFit\Extension\Shop\Models\Customer;
use MayIFit\Extension\Shop\Models\Order;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Order::class, 100)->make()
        ->each(function($order) {
            $products = factory(Product::class, 10)->create();
            $randomCustomer = Customer::all()->random(1)->first();
            $order->customer()->associate($randomCustomer->id);
            $order->save();
            foreach ($products as $product) {
                $data = array('quantity' => rand(1, 100));
                $order->products()->attach($product->catalog_id, $data);
            }
            $order->save();
            $products = $order->products()->get();
            foreach ($products as $product) {
                $order->net_value += $product->net_price * $product->pivot->quantity;
                $order->value += ($product->net_price * (1 + ($product->vat / 100))) * $product->pivot->quantity;
                $order->order_quantity = $product->pivot->quantity;
            }
            
            $order->save();
        });
    }
}
