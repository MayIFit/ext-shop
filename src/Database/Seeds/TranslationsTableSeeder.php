<?php

namespace MayIFit\Extension\Shop\Database\Seeds;

use Illuminate\Database\Seeder;
use MayIFit\Core\Translation\Models\Translation;

/**
 * Class TranslationsTableSeeder
 *
 * @package MayIFit\Extension\Shop
 */
class TranslationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->addUnitTranslations();
		$this->addProductRelatedTranslations();
        $this->addShopRelatedTranslations();
		$this->addOrderRelatedTranslations();
		$this->addPricingRelatedTranslations();
		$this->addDiscountRelatedTranslations();
		$this->addUserRelatedTranslations();
		$this->addResellerRelatedTranslations();
		$this->globalTranslations();
	}
	
	protected function globalTranslations() {
        Translation::updateOrCreate([
				'group' => 'global',
				'key' => 'store'
			],
			['text' => ['en' => 'store management', 'hu' => 'üzlet menedzsment']],
		);
		Translation::updateOrCreate([
				'group' => 'global',
				'key' => 'shop'
			],
			['text' => ['en' => 'shop', 'hu' => 'üzlet']],
		);
		Translation::updateOrCreate([
				'group' => 'list',
				'key' => 'product'
			],
			['text' => ['en' => 'product list', 'hu' => 'termék lista']],
		);
		Translation::updateOrCreate([
				'group' => 'list',
				'key' => 'product-category'
			],
			['text' => ['en' => 'product category list', 'hu' => 'termék kategória lista']],
		);
		Translation::updateOrCreate([
				'group' => 'list',
				'key' => 'product-pricing'
			],
			['text' => ['en' => 'product pricing list', 'hu' => 'termék árazás lista']],
		);
		Translation::updateOrCreate([
				'group' => 'list',
				'key' => 'order'
			],
			['text' => ['en' => 'order list', 'hu' => 'rendelés lista']],
		);
		Translation::updateOrCreate([
				'group' => 'order',
				'key' => 'gross_price'
			],
			['text' => ['en' => 'order gross price', 'hu' => 'rendelés bruttó összeg']],
		);
		Translation::updateOrCreate([
				'group' => 'order',
				'key' => 'quantity'
			],
			['text' => ['en' => 'order quantity', 'hu' => 'rendelés mennyisége']],
		);
		Translation::updateOrCreate([
				'group' => 'order',
				'key' => 'products'
			],
			['text' => ['en' => 'ordered products', 'hu' => 'rendelt termékek']],
		);
		Translation::updateOrCreate([
				'group' => 'order',
				'key' => 'paid'
			],
			['text' => ['en' => 'paid', 'hu' => 'fizetve']],
		);
		Translation::updateOrCreate([
				'group' => 'order',
				'key' => 'placed'
			],
			['text' => ['en' => 'placed', 'hu' => 'leadva']],
		);
		Translation::updateOrCreate([
				'group' => 'global',
				'key' => 'reviews'
			],
			['text' => ['en' => 'reviews', 'hu' => 'értékelések']],
		);
	}

    protected function addUnitTranslations() {
        Translation::updateOrCreate([
	            'group' => 'unit',
	            'key' => 'pack'
			],
            ['text' => ['en' => 'pack', 'hu' => 'kiszerelés']],
		);
        Translation::updateOrCreate([
	            'group' => 'unit',
	            'key' => 'piece'
			],
            ['text' => ['en' => 'piece', 'hu' => 'darab']],
		);
        Translation::updateOrCreate([
	            'group' => 'unit',
	            'key' => 'pallet'
			],
            ['text' => ['en' => 'pallet', 'hu' => 'raklap']],
		);
        Translation::updateOrCreate([
	            'group' => 'unit',
	            'key' => 'package'
			],
            ['text' => ['en' => 'package', 'hu' => 'csomag']],
		);
    }

    protected function addProductRelatedTranslations() {
        Translation::updateOrCreate([
	            'group' => 'product',
	            'key' => 'catalog_id'
			],
            ['text' => ['en' => 'catalog id', 'hu' => 'katalógusszám']],
		);
        Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'quality'
			],
			['text' => ['en' => 'quality', 'hu' => 'minőség']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'name'
			],
			['text' => ['en' => 'product name', 'hu' => 'termék neve']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'description'
			],
			['text' => ['en' => 'product description', 'hu' => 'termék leírása']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'technical_specs'
			],
			['text' => ['en' => 'tehcnical specifications', 'hu' => 'műszaki adatok']],
		);
        Translation::updateOrCreate([
	            'group' => 'product',
	            'key' => 'net_price'
			],
            ['text' => ['en' => 'net price', 'hu' => 'nettó ár']],
		);
        Translation::updateOrCreate([
	            'group' => 'product',
	            'key' => 'gross_price'
			],
            ['text' => ['en' => 'gross price', 'hu' => 'bruttó ár']],
		);
        Translation::updateOrCreate([
	            'group' => 'product',
	            'key' => 'vat'
			],
            ['text' => ['en' => 'vat', 'hu' => 'áfa']],
		);
        Translation::updateOrCreate([
	            'group' => 'product',
	            'key' => 'quantity'
			],
            ['text' => ['en' => 'quantity', 'hu' => 'mennyiség']],
		);
        Translation::updateOrCreate([
	            'group' => 'product',
	            'key' => 'category'
			],
            ['text' => ['en' => 'product category', 'hu' => 'termék kategória']],
		);
        Translation::updateOrCreate([
	            'group' => 'product',
	            'key' => 'documents'
			],
            ['text' => ['en' => 'product documents', 'hu' => 'termék dokumentumok']],
		);
        Translation::updateOrCreate([
	            'group' => 'product',
	            'key' => 'discount'
			],
            ['text' => ['en' => 'discount', 'hu' => 'kedvezmény']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'in_stock'
			],
			['text' => ['en' => 'in stock', 'hu' => 'raktáron']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'discount_percentage'
			],
			['text' => ['en' => 'discount percentage', 'hu' => 'kedvezmény százalék']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'parent_product_id'
			],
			['text' => ['en' => 'parent product', 'hu' => 'szülő termék']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'out_of_stock_text'
			],
			['text' => ['en' => 'out of stock text', 'hu' => 'kifogyott szöveg']],
		);
		Translation::updateOrCreate([
			'group' => 'product',
				'key' => 'quantity_unit_text'
			],
			['text' => ['en' => 'quantity unit', 'hu' => 'csomag']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'discounts'
			],
			['text' => ['en' => 'discounts', 'hu' => 'kedvezmények']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'varranty'
			],
			['text' => ['en' => 'varranty', 'hu' => 'garancia']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'supplied'
			],
			['text' => ['en' => 'supplied', 'hu' => 'csomag tartalma']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'pricings'
			],
			['text' => ['en' => 'pricings', 'hu' => 'árazás']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'refurbished'
			],
			['text' => ['en' => 'refurbished', 'hu' => 'felújított']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'not_available_for_order'
			],
			['text' => ['en' => 'currently unavailable', 'hu' => 'jelenleg nem rendelhető']],
		);


		Translation::updateOrCreate([
				'group' => 'supplied',
				'key' => 'name'
			],
			['text' => ['en' => 'supplied item name', 'hu' => 'csomagtétel neve']],
		);
		Translation::updateOrCreate([
				'group' => 'supplied',
				'key' => 'value'
			],
			['text' => ['en' => 'supplied item value', 'hu' => 'csomagtétel értéke']],
		);
		Translation::updateOrCreate([
				'group' => 'technical_specs',
				'key' => 'name'
			],
			['text' => ['en' => 'technical specification attribute', 'hu' => 'specifikáció tulajdonság']],
		);
		Translation::updateOrCreate([
				'group' => 'technical_specs',
				'key' => 'value'
			],
			['text' => ['en' => 'technical specification value', 'hu' => 'specifikáció érték']],
		);
    }

    protected function addOrderRelatedTranslations() {
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'order'
			],
            ['text' => ['en' => 'order', 'hu' => 'rendelés']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'final_net_price'
			],
            ['text' => ['en' => 'total net price', 'hu' => 'teljes nettó végösszeg']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'final_gross_price'
			],
            ['text' => ['en' => 'total gross price', 'hu' => 'teljes bruttó végösszeg']],
		);

        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'total_net_price'
			],
            ['text' => ['en' => 'total net price', 'hu' => 'teljes nettó összeg']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'total_gross_price'
			],
            ['text' => ['en' => 'total gross price', 'hu' => 'teljes bruttó összeg']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'shipping_address'
			],
            ['text' => ['en' => 'shipping address', 'hu' => 'szállítási adatok']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'billing_address'
			],
            ['text' => ['en' => 'billing address', 'hu' => 'számlázási adatok']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'track_order_status'
			],
            ['text' => ['en' => 'track order status', 'hu' => 'rendelés követés']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'order_token'
			],
            ['text' => ['en' => 'order token', 'hu' => 'rendelési azonosító']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'customer'
			],
            ['text' => ['en' => 'customer', 'hu' => 'ügyfél']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'title'
			],
            ['text' => ['en' => 'title', 'hu' => 'titulus']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'first_name'
			],
            ['text' => ['en' => 'first name', 'hu' => 'vezetéknév']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'last_name'
			],
            ['text' => ['en' => 'last name', 'hu' => 'utónév']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'country'
			],
            ['text' => ['en' => 'country', 'hu' => 'ország']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'city'
			],
            ['text' => ['en' => 'city', 'hu' => 'város']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'zip_code'
			],
            ['text' => ['en' => 'zip code', 'hu' => 'irányítószám']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'address'
			],
            ['text' => ['en' => 'address', 'hu' => 'közterület neve']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'house_nr'
			],
            ['text' => ['en' => 'house number', 'hu' => 'házszám']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'floor'
			],
            ['text' => ['en' => 'floor', 'hu' => 'emelet']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'door'
			],
            ['text' => ['en' => 'door', 'hu' => 'ajtó']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'billing_vat_number'
			],
            ['text' => ['en' => 'billing vat number', 'hu' => 'adószám']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'different_billing'
			],
            ['text' => ['en' => 'different billing address', 'hu' => 'eltérő számlázási adatok']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'billing_same_as_shipping_address'
			],
            ['text' => ['en' => 'billing address is same as shipping address', 'hu' => 'számlázási cím megegyezik a szállítási címmel']],
		);

    }

    protected function addShopRelatedTranslations() {
        Translation::updateOrCreate([
	            'group' => 'action',
	            'key' => 'add_to_cart'
			],
            ['text' => ['en' => 'add to cart', 'hu' => 'kosárba rakás']],
		);
		Translation::updateOrCreate([
				'group' => 'action',
				'key' => 'upload'
			],
			['text' => ['en' => 'upload', 'hu' => 'feltöltés']],
		);
		Translation::updateOrCreate([
				'group' => 'action',
				'key' => 'register_reseller'
			],
			['text' => ['en' => 'reseller registration', 'hu' => 'viszonteladói regisztráció']],
		);
        Translation::updateOrCreate([
	            'group' => 'shop',
	            'key' => 'shop'
			],
            ['text' => ['en' => 'shop', 'hu' => 'üzlet']],
		);
        Translation::updateOrCreate([
	            'group' => 'shop',
	            'key' => 'products'
			],
            ['text' => ['en' => 'products', 'hu' => 'termékek']],
		);
        Translation::updateOrCreate([
	            'group' => 'shop',
	            'key' => 'categories'
			],
            ['text' => ['en' => 'categories', 'hu' => 'kategóriák']],
		);
		Translation::updateOrCreate([
				'group' => 'shop',
				'key' => 'cart'
			],
			['text' => ['en' => 'shopping cart', 'hu' => 'bevásárló kosár']],
		);
		Translation::updateOrCreate([
				'group' => 'shop',
				'key' => 'order'
			],
			['text' => ['en' => 'order', 'hu' => 'rendelés']],
		);
	}
	
	protected function addPricingRelatedTranslations() {
        Translation::updateOrCreate([
	            'group' => 'pricings',
	            'key' => 'base_price'
			],
            ['text' => ['en' => 'net price', 'hu' => 'nettó érték']],
		);
        Translation::updateOrCreate([
	            'group' => 'pricings',
	            'key' => 'vat'
			],
            ['text' => ['en' => 'vat', 'hu' => 'áfa']],
		);
        Translation::updateOrCreate([
	            'group' => 'pricings',
	            'key' => 'currency'
			],
            ['text' => ['en' => 'currency', 'hu' => 'pénznem']],
		);
        Translation::updateOrCreate([
	            'group' => 'pricings',
	            'key' => 'available_to'
			],
            ['text' => ['en' => 'available to', 'hu' => 'érvényes']],
		);
	}

	protected function addCategoryRelatedTranslations() {
		Translation::updateOrCreate([
				'group' => 'product-category',
				'key' => 'name'
			],
			['text' => ['en' => 'category name', 'hu' => 'kategória név']],
		);
		Translation::updateOrCreate([
				'group' => 'product-category',
				'key' => 'description'
			],
			['text' => ['en' => 'category description', 'hu' => 'kategória leírás']],
		);
		Translation::updateOrCreate([
				'group' => 'product-category',
				'key' => 'parentcategory'
			],
			['text' => ['en' => 'parent category', 'hu' => 'szülő kategória']],
		);
	}
	
	protected function addDiscountRelatedTranslations() {
        Translation::updateOrCreate([
	            'group' => 'discounts',
	            'key' => 'discount_percentage'
			],
            ['text' => ['en' => 'discount percentage', 'hu' => 'kedvezmény százalék']],
		);
        Translation::updateOrCreate([
	            'group' => 'discounts',
	            'key' => 'available_from'
			],
            ['text' => ['en' => 'available from', 'hu' => 'tól']],
		);
        Translation::updateOrCreate([
	            'group' => 'discounts',
	            'key' => 'available_to'
			],
            ['text' => ['en' => 'available to', 'hu' => 'ig']],
		);
	}
	
	protected function addUserRelatedTranslations() {
		Translation::updateOrCreate([
				'group' => 'user',
				'key' => 'customer'
			],
			['text' => ['en' => 'customer', 'hu' => 'vásárló']],
		);
		Translation::updateOrCreate([
				'group' => 'user',
				'key' => 'reseller'
			],
			['text' => ['en' => 'reseller', 'hu' => 'viszonteladó']],
		);
	}	
	protected function addResellerRelatedTranslations() {
		Translation::updateOrCreate([
				'group' => 'list',
				'key' => 'reseller'
			],
			['text' => ['en' => 'list reseller', 'hu' => 'viszonteladói lista']],
		);
		Translation::updateOrCreate([
				'group' => 'list',
				'key' => 'reseller-group'
			],
			['text' => ['en' => 'list reseller group', 'hu' => 'viszonteladói csoport lista']],
		);
		Translation::updateOrCreate([
				'group' => 'list',
				'key' => 'reseller-product-pricing'
			],
			['text' => ['en' => 'list products', 'hu' => 'termék lista']],
		);
		Translation::updateOrCreate([
				'group' => 'reseller',
				'key' => 'company_name'
			],
			['text' => ['en' => 'reseller company name', 'hu' => 'viszonteladói cég név']],
		);
		Translation::updateOrCreate([
				'group' => 'reseller',
				'key' => 'vat_id'
			],
			['text' => ['en' => 'reseller vat id', 'hu' => 'viszonteladói adószám']],
		);
		Translation::updateOrCreate([
				'group' => 'reseller',
				'key' => 'contact_person'
			],
			['text' => ['en' => 'reseller contact person', 'hu' => 'viszonteladói kapcsolattartó']],
		);
		Translation::updateOrCreate([
				'group' => 'reseller',
				'key' => 'email'
			],
			['text' => ['en' => 'reseller email', 'hu' => 'viszonteladói e-mail']],
		);
		Translation::updateOrCreate([
				'group' => 'reseller',
				'key' => 'phone_number'
			],
			['text' => ['en' => 'reseller phone number', 'hu' => 'viszonteladói telefonszám']],
		);
		Translation::updateOrCreate([
				'group' => 'reseller',
				'key' => 'supplier_customer_code'
			],
			['text' => ['en' => 'supplier customer code', 'hu' => 'szállító kód']],
		);
		Translation::updateOrCreate([
				'group' => 'global',
				'key' => 'reseller'
			],
			['text' => ['en' => 'reseller', 'hu' => 'viszonteladó']],
		);
	}
}
