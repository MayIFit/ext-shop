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
        SystemSetting::firstOrCreate([
			'setting_name' => 'shop.emailFrom',
			'setting_description' => 'The email address from which messages are sent from',
			'setting_value' => 'tischler.kristof@gmail.com'
		]);
		SystemSetting::firstOrCreate([
			'setting_name' => 'shop.emailFromName',
			'setting_description' => 'The email sender name from which messages are sent from',
			'setting_value' => 'Güde'
        ]);
        SystemSetting::firstOrCreate([
			'setting_name' => 'shop.name',
			'setting_description' => 'The name of the E-commerce application',
            'setting_value' => 'GÜDE Webshop',
            'public' => true
		]);
    }
}
