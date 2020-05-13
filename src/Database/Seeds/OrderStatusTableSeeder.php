<?php

namespace MayIFit\Extension\Shop\Database\Seeds;

use Illuminate\Database\Seeder;
use MayIFit\Extension\Shop\Models\OrderStatus;

/**
 * Class OrderStatusTableSeeder
 *
 * @package MayIFit\Extension\Shop
 */
class OrderStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderStatus::updateOrCreate([
			'status' => 'placed',
			'icon' => ''
		]);

		OrderStatus::updateOrCreate([
			'status' => 'approved',
			'icon' => ''
		]);

		OrderStatus::updateOrCreate([
			'status' => 'handed_over_for_shipping',
			'icon' => ''
		]);

		OrderStatus::updateOrCreate([
			'status' => 'declined',
			'icon' => ''
		]);

		OrderStatus::updateOrCreate([
			'status' => 'in_delivery',
			'icon' => ''
		]);

		OrderStatus::updateOrCreate([
			'status' => 'delivered',
			'icon' => ''
		]);
    }
}
