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
			'name' => 'placed',
			'icon' => ''
		]);

		OrderStatus::updateOrCreate([
			'name' => 'approved',
			'icon' => ''
		]);

		OrderStatus::updateOrCreate([
			'name' => 'handed_over_for_shipping',
			'icon' => ''
		]);

		OrderStatus::updateOrCreate([
			'name' => 'declined',
			'icon' => ''
		]);

		OrderStatus::updateOrCreate([
			'name' => 'in_delivery',
			'icon' => ''
		]);

		OrderStatus::updateOrCreate([
			'name' => 'delivered',
			'icon' => ''
		]);
    }
}
