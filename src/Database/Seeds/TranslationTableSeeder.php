<?php

namespace MayIFit\Extension\Shop\Database\Seeds;

use Illuminate\Database\Seeder;
use MayIFit\Core\Translation\Models\Translation;

/**
 * Class TranslationsTableSeeder
 *
 * @package MayIFit\Extension\Shop
 */
class TranslationTableSeeder extends Seeder
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
    }

    protected function addUnitTranslations() {
        Translation::firstOrCreate([
            'group' => 'unit',
            'key' => 'pack',
            'text' => ['en' => 'Pack', 'hu' => 'Kiszerelés'],
        ]);
        Translation::firstOrCreate([
            'group' => 'unit',
            'key' => 'piece',
            'text' => ['en' => 'Piece', 'hu' => 'Darab'],
        ]);
        Translation::firstOrCreate([
            'group' => 'unit',
            'key' => 'pallet',
            'text' => ['en' => 'Pallet', 'hu' => 'Raklap'],
        ]);
        Translation::firstOrCreate([
            'group' => 'unit',
            'key' => 'package',
            'text' => ['en' => 'Package', 'hu' => 'Csomag'],
        ]);
    }

    protected function addProductRelatedTranslations() {
        Translation::firstOrCreate([
            'group' => 'product',
            'key' => 'catalog_id',
            'text' => ['en' => 'Catalog ID', 'hu' => 'Katalógusszám'],
        ]);
        Translation::firstOrCreate([
            'group' => 'product',
            'key' => 'net_price',
            'text' => ['en' => 'Net Price', 'hu' => 'Nettó Ár'],
        ]);
        Translation::firstOrCreate([
            'group' => 'product',
            'key' => 'gross_price',
            'text' => ['en' => 'Gross Price', 'hu' => 'Bruttó Ár'],
        ]);
        Translation::firstOrCreate([
            'group' => 'product',
            'key' => 'vat',
            'text' => ['en' => 'Vat', 'hu' => 'ÁFA'],
        ]);
        Translation::firstOrCreate([
            'group' => 'product',
            'key' => 'quantity',
            'text' => ['en' => 'Quantity', 'hu' => 'Mennyiség'],
        ]);
        Translation::firstOrCreate([
            'group' => 'product',
            'key' => 'category',
            'text' => ['en' => 'Product Category', 'hu' => 'Termék Kategória'],
        ]);
        Translation::firstOrCreate([
            'group' => 'product',
            'key' => 'documents',
            'text' => ['en' => 'Product Documents', 'hu' => 'Termék Dokumentumok'],
        ]);
        Translation::firstOrCreate([
            'group' => 'product',
            'key' => 'discount',
            'text' => ['en' => 'Discount', 'hu' => 'Kedvezmény'],
        ]);
    }

    protected function addOrderRelatedTranslations() {
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'order',
            'text' => ['en' => 'Order', 'hu' => 'Rendelés'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'final_net_price',
            'text' => ['en' => 'Total Net Price', 'hu' => 'Teljes Nettó Végösszeg'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'final_gross_price',
            'text' => ['en' => 'Total Gross Price', 'hu' => 'Teljes Bruttó Végösszeg'],
        ]);

        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'total_net_price',
            'text' => ['en' => 'Total Net Price', 'hu' => 'Teljes Nettó Összeg'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'total_gross_price',
            'text' => ['en' => 'Total Gross Price', 'hu' => 'Teljes Bruttó Összeg'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'shipping_address',
            'text' => ['en' => 'Shipping Address', 'hu' => 'Szállítási Adatok'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'billing_address',
            'text' => ['en' => 'Billing Address', 'hu' => 'Számlázási adatok'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'track_order_status',
            'text' => ['en' => 'Track Order Status', 'hu' => 'Rendelés Követés'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'order_token',
            'text' => ['en' => 'Order Token', 'hu' => 'Rendelési Azonosító'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'customer',
            'text' => ['en' => 'Customer', 'hu' => 'Ügyfél'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'title',
            'text' => ['en' => 'Title', 'hu' => 'Titulus'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'first_name',
            'text' => ['en' => 'First Name', 'hu' => 'Vezetéknév'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'last_name',
            'text' => ['en' => 'Last Name', 'hu' => 'Utónév'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'country',
            'text' => ['en' => 'Country', 'hu' => 'Ország'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'city',
            'text' => ['en' => 'City', 'hu' => 'Város'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'zip_code',
            'text' => ['en' => 'Zip Code', 'hu' => 'Irányítószám'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'address',
            'text' => ['en' => 'Address', 'hu' => 'Közterület neve'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'house_nr',
            'text' => ['en' => 'House Number', 'hu' => 'Házszám'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'floor',
            'text' => ['en' => 'Floor', 'hu' => 'Emelet'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'door',
            'text' => ['en' => 'Door', 'hu' => 'Ajtó'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'billing_vat_number',
            'text' => ['en' => 'Billing Vat Number', 'hu' => 'Adószám'],
        ]);
        Translation::firstOrCreate([
            'group' => 'order',
            'key' => 'different_billing',
            'text' => ['en' => 'Different Billing Address', 'hu' => 'Eltérő Számlázási Adatok'],
        ]);

    }

    protected function addShopRelatedTranslations() {
        Translation::firstOrCreate([
            'group' => 'actions',
            'key' => 'add_to_cart',
            'text' => ['en' => 'Add To Cart', 'hu' => 'Kosárba Rakás'],
        ]);
        Translation::firstOrCreate([
            'group' => 'shop',
            'key' => 'shop',
            'text' => ['en' => 'Shop', 'hu' => 'Üzlet'],
        ]);
       
        Translation::firstOrCreate([
            'group' => 'shop',
            'key' => 'products',
            'text' => ['en' => 'Products', 'hu' => 'Termékek'],
        ]);
        Translation::firstOrCreate([
            'group' => 'shop',
            'key' => 'categories',
            'text' => ['en' => 'Categories', 'hu' => 'Kategóriák'],
        ]);
    }
}
