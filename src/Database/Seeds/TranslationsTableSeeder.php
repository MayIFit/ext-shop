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
    }

    protected function addUnitTranslations() {
        Translation::updateOrCreate([
	            'group' => 'unit',
	            'key' => 'pack'
			],
            ['text' => ['en' => 'Pack', 'hu' => 'Kiszerelés']],
		);
        Translation::updateOrCreate([
	            'group' => 'unit',
	            'key' => 'piece'
			],
            ['text' => ['en' => 'Piece', 'hu' => 'Darab']],
		);
        Translation::updateOrCreate([
	            'group' => 'unit',
	            'key' => 'pallet'
			],
            ['text' => ['en' => 'Pallet', 'hu' => 'Raklap']],
		);
        Translation::updateOrCreate([
	            'group' => 'unit',
	            'key' => 'package'
			],
            ['text' => ['en' => 'Package', 'hu' => 'Csomag']],
		);
    }

    protected function addProductRelatedTranslations() {
        Translation::updateOrCreate([
	            'group' => 'product',
	            'key' => 'catalog_id'
			],
            ['text' => ['en' => 'Catalog ID', 'hu' => 'Katalógusszám']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'name'
			],
			['text' => ['en' => 'Product Name', 'hu' => 'Termék Neve']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'description'
			],
			['text' => ['en' => 'Product Description', 'hu' => 'Termék Leírása']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'technical_specs'
			],
			['text' => ['en' => 'Tehcnical Specifications', 'hu' => 'Műszaki Adatok']],
		);
        Translation::updateOrCreate([
	            'group' => 'product',
	            'key' => 'net_price'
			],
            ['text' => ['en' => 'Net Price', 'hu' => 'Nettó Ár']],
		);
        Translation::updateOrCreate([
	            'group' => 'product',
	            'key' => 'gross_price'
			],
            ['text' => ['en' => 'Gross Price', 'hu' => 'Bruttó Ár']],
		);
        Translation::updateOrCreate([
	            'group' => 'product',
	            'key' => 'vat'
			],
            ['text' => ['en' => 'Vat', 'hu' => 'ÁFA']],
		);
        Translation::updateOrCreate([
	            'group' => 'product',
	            'key' => 'quantity'
			],
            ['text' => ['en' => 'Quantity', 'hu' => 'Mennyiség']],
		);
        Translation::updateOrCreate([
	            'group' => 'product',
	            'key' => 'category'
			],
            ['text' => ['en' => 'Product Category', 'hu' => 'Termék Kategória']],
		);
        Translation::updateOrCreate([
	            'group' => 'product',
	            'key' => 'documents'
			],
            ['text' => ['en' => 'Product Documents', 'hu' => 'Termék Dokumentumok']],
		);
        Translation::updateOrCreate([
	            'group' => 'product',
	            'key' => 'discount'
			],
            ['text' => ['en' => 'Discount', 'hu' => 'Kedvezmény']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'in_stock'
			],
			['text' => ['en' => 'In Stock', 'hu' => 'Raktáron']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'discount_percentage'
			],
			['text' => ['en' => 'Discount Percentage', 'hu' => 'Kedvezmény Százalék']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'parent_product_id'
			],
			['text' => ['en' => 'Parent Product', 'hu' => 'Szülő Termék']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'out_of_stock_text'
			],
			['text' => ['en' => 'Out of Stock Text', 'hu' => 'Kifogyott szöveg']],
		);
		Translation::updateOrCreate([
			'group' => 'product',
				'key' => 'quantity_unit_text'
			],
			['text' => ['en' => 'Quantity Unit', 'hu' => 'Csomag']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'discounts'
			],
			['text' => ['en' => 'Discounts', 'hu' => 'Kedvezmények']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'varranty'
			],
			['text' => ['en' => 'Varranty', 'hu' => 'Garancia']],
		);
		Translation::updateOrCreate([
				'group' => 'product',
				'key' => 'supplied'
			],
			['text' => ['en' => 'Supplied', 'hu' => 'Csomag tartalma']],
		);
    }

    protected function addOrderRelatedTranslations() {
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'order'
			],
            ['text' => ['en' => 'Order', 'hu' => 'Rendelés']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'final_net_price'
			],
            ['text' => ['en' => 'Total Net Price', 'hu' => 'Teljes Nettó Végösszeg']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'final_gross_price'
			],
            ['text' => ['en' => 'Total Gross Price', 'hu' => 'Teljes Bruttó Végösszeg']],
		);

        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'total_net_price'
			],
            ['text' => ['en' => 'Total Net Price', 'hu' => 'Teljes Nettó Összeg']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'total_gross_price'
			],
            ['text' => ['en' => 'Total Gross Price', 'hu' => 'Teljes Bruttó Összeg']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'shipping_address'
			],
            ['text' => ['en' => 'Shipping Address', 'hu' => 'Szállítási Adatok']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'billing_address'
			],
            ['text' => ['en' => 'Billing Address', 'hu' => 'Számlázási adatok']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'track_order_status'
			],
            ['text' => ['en' => 'Track Order Status', 'hu' => 'Rendelés Követés']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'order_token'
			],
            ['text' => ['en' => 'Order Token', 'hu' => 'Rendelési Azonosító']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'customer'
			],
            ['text' => ['en' => 'Customer', 'hu' => 'Ügyfél']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'title'
			],
            ['text' => ['en' => 'Title', 'hu' => 'Titulus']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'first_name'
			],
            ['text' => ['en' => 'First Name', 'hu' => 'Vezetéknév']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'last_name'
			],
            ['text' => ['en' => 'Last Name', 'hu' => 'Utónév']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'country'
			],
            ['text' => ['en' => 'Country', 'hu' => 'Ország']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'city'
			],
            ['text' => ['en' => 'City', 'hu' => 'Város']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'zip_code'
			],
            ['text' => ['en' => 'Zip Code', 'hu' => 'Irányítószám']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'address'
			],
            ['text' => ['en' => 'Address', 'hu' => 'Közterület neve']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'house_nr'
			],
            ['text' => ['en' => 'House Number', 'hu' => 'Házszám']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'floor'
			],
            ['text' => ['en' => 'Floor', 'hu' => 'Emelet']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'door'
			],
            ['text' => ['en' => 'Door', 'hu' => 'Ajtó']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'billing_vat_number'
			],
            ['text' => ['en' => 'Billing Vat Number', 'hu' => 'Adószám']],
		);
        Translation::updateOrCreate([
	            'group' => 'order',
	            'key' => 'different_billing'
			],
            ['text' => ['en' => 'Different Billing Address', 'hu' => 'Eltérő Számlázási Adatok']],
		);

    }

    protected function addShopRelatedTranslations() {
        Translation::updateOrCreate([
	            'group' => 'action',
	            'key' => 'add_to_cart'
			],
            ['text' => ['en' => 'Add To Cart', 'hu' => 'Kosárba Rakás']],
		);
        Translation::updateOrCreate([
	            'group' => 'shop',
	            'key' => 'shop'
			],
            ['text' => ['en' => 'Shop', 'hu' => 'Üzlet']],
		);
        Translation::updateOrCreate([
	            'group' => 'shop',
	            'key' => 'products'
			],
            ['text' => ['en' => 'Products', 'hu' => 'Termékek']],
		);
        Translation::updateOrCreate([
	            'group' => 'shop',
	            'key' => 'categories'
			],
            ['text' => ['en' => 'Categories', 'hu' => 'Kategóriák']],
		);
		Translation::updateOrCreate([
				'group' => 'shop',
				'key' => 'cart'
			],
			['text' => ['en' => 'Shopping Cart', 'hu' => 'Bevásárló Kosár']],
		);
		Translation::updateOrCreate([
				'group' => 'shop',
				'key' => 'order'
			],
			['text' => ['en' => 'Order', 'hu' => 'Rendelés']],
		);
	}
	
	protected function addPricingRelatedTranslations() {
        Translation::updateOrCreate([
	            'group' => 'pricings',
	            'key' => 'base_price'
			],
            ['text' => ['en' => 'Net Price', 'hu' => 'Nettó Érték']],
		);
        Translation::updateOrCreate([
	            'group' => 'pricings',
	            'key' => 'vat'
			],
            ['text' => ['en' => 'Vat', 'hu' => 'ÁFA']],
		);
        Translation::updateOrCreate([
	            'group' => 'pricings',
	            'key' => 'currency'
			],
            ['text' => ['en' => 'Currency', 'hu' => 'Pénznem']],
		);
	}
	
	protected function addDiscountRelatedTranslations() {
        Translation::updateOrCreate([
	            'group' => 'discounts',
	            'key' => 'discount_percentage'
			],
            ['text' => ['en' => 'Discount Percentage', 'hu' => 'Kedvezmény Százalék']],
		);
        Translation::updateOrCreate([
	            'group' => 'discounts',
	            'key' => 'available_from'
			],
            ['text' => ['en' => 'Available From', 'hu' => 'Tól']],
		);
        Translation::updateOrCreate([
	            'group' => 'discounts',
	            'key' => 'available_to'
			],
            ['text' => ['en' => 'Available To', 'hu' => 'Ig']],
		);
    }
}
