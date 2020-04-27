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
            $products = factory(Product::class, 10)->create()->pluck('catalog_id');
            $randomCustomer = Customer::all()->random()->pluck('id');

            $syncableData = array();

            foreach ($products as $product) {
                $data = array('quantity' => rand(1, 100));
                array_push($syncableData, $product);
                array_push($syncableData[$product], $data);
            }

            $order->products()->sync($syncableData);
            foreach ($products as $product) {
                $order->net_value += $product->net_price * $model->quantity;
                $order->value += ($product->net_price * (1 + ($product->vat / 100))) * $syncableData[$product]->quantity;
                $order->order_quantity = $model->quantity;
            }
            $order->customer()->attach($randomCustomer);
            $order->save();
        });
    }
}
