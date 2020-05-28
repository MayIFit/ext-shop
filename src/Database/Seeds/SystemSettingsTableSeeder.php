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
			'setting_name' => 'shop.emailFrom',
			'setting_description' => 'shop.email_from',
			'setting_value' => 'info@mayifit.net'
		]);
		SystemSetting::updateOrCreate([
			'setting_name' => 'shop.emailFromName',
			'setting_description' => 'shop.email_from_name',
			'setting_value' => 'info@mayifit.net'
		]);
    }
}
