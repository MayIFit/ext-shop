<?php

namespace MayIFit\Extension\Shop\Database\Seeds;

use Illuminate\Database\Seeder;
use MayIFit\Core\Permission\Models\SystemSetting;

/**
 * Class TranslationsTableSeeder
 *
 * @package MayIFit\Extension\Shop
 */
class SystemSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->addMailingRelatedSettings();
    }

	protected function addMailingRelatedSettings() {
        SystemSetting::updateOrCreate([
			'name' => 'shop.emailFrom',
			'description' => 'shop.email_from',
			'value' => 'info@mayifit.net'
		]);
		SystemSetting::updateOrCreate([
			'name' => 'shop.emailFromName',
			'description' => 'shop.email_from_name',
			'value' => 'info@mayifit.net'
		]);
    }
}
