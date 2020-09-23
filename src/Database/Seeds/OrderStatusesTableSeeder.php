<?php

namespace MayIFit\Extension\Shop\Database\Seeds;

use Illuminate\Database\Seeder;
use MayIFit\Extension\Shop\Models\OrderStatus;

/**
 * Class OrderStatusesTableSeeder
 *
 * @package MayIFit\Extension\Shop
 */
class OrderStatusesTableSeeder extends Seeder
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
			'icon' => '',
			'send_notification' => true
		]);

		OrderStatus::updateOrCreate([
			'name' => 'waiting_for_payment',
			'icon' => '',
			'send_notification' => false
		]);

		OrderStatus::updateOrCreate([
			'name' => 'approved',
			'icon' => '',
			'send_notification' => true
		]);

		OrderStatus::updateOrCreate([
			'name' => 'handed_over_for_shipping',
			'icon' => '',
			'send_notification' => true
		]);

		OrderStatus::updateOrCreate([
			'name' => 'in_transit',
			'icon' => '',
			'send_notification' => true
		]);

		OrderStatus::updateOrCreate([
			'name' => 'declined',
			'icon' => '',
			'send_notification' => true
		]);
	}
}
