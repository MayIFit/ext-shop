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
    }

    protected function addUnitTranslations() {
        Translation::create([
            'group' => 'unit',
            'key' => 'pack',
            'text' => ['en' => 'Pack', 'hu' => 'Kiszerelés'],
        ]);
        Translation::create([
            'group' => 'unit',
            'key' => 'piece',
            'text' => ['en' => 'Piece', 'hu' => 'Darab'],
        ]);
        Translation::create([
            'group' => 'unit',
            'key' => 'pallet',
            'text' => ['en' => 'Pallet', 'hu' => 'Raklap'],
        ]);
        Translation::create([
            'group' => 'unit',
            'key' => 'package',
            'text' => ['en' => 'Package', 'hu' => 'Csomag'],
        ]);
    }

    protected function addProductRelatedTranslations() {
        Translation::create([
            'group' => 'product',
            'key' => 'net_price',
            'text' => ['en' => 'Net Price', 'hu' => 'Nettó Ár'],
        ]);
        Translation::create([
            'group' => 'product',
            'key' => 'vat',
            'text' => ['en' => 'Vat', 'hu' => 'ÁFA'],
        ]);
        Translation::create([
            'group' => 'product',
            'key' => 'quantity',
            'text' => ['en' => 'Quantity', 'hu' => 'Mennyiség'],
        ]);
        Translation::create([
            'group' => 'product',
            'key' => 'category',
            'text' => ['en' => 'Product Category', 'hu' => 'Termék Kategória'],
        ]);
        Translation::create([
            'group' => 'product',
            'key' => 'documents',
            'text' => ['en' => 'Product Documents', 'hu' => 'Termék Dokumentumok'],
        ]);
    }

    protected function addOrderRelatedTranslations() {
        Translation::create([
            'group' => 'order',
            'key' => 'order',
            'text' => ['en' => 'Order', 'hu' => 'Rendelés'],
        ]);
        Translation::create([
            'group' => 'order',
            'key' => 'shipping_address',
            'text' => ['en' => 'Shipping Address', 'hu' => 'Szállítási Adatok'],
        ]);
        Translation::create([
            'group' => 'order',
            'key' => 'track_order_status',
            'text' => ['en' => 'Track Order Status', 'hu' => 'Rendelés Követés'],
        ]);
        Translation::create([
            'group' => 'order',
            'key' => 'order_token',
            'text' => ['en' => 'Order Token', 'hu' => 'Rendelési Azonosító'],
        ]);
        Translation::create([
            'group' => 'order',
            'key' => 'customer',
            'text' => ['en' => 'Customer', 'hu' => 'Ügyfél'],
        ]);
        Translation::create([
            'group' => 'order',
            'key' => 'title',
            'text' => ['en' => 'Title', 'hu' => 'Titulus'],
        ]);
        Translation::create([
            'group' => 'order',
            'key' => 'first_name',
            'text' => ['en' => 'First Name', 'hu' => 'Vezetéknév'],
        ]);
        Translation::create([
            'group' => 'order',
            'key' => 'last_name',
            'text' => ['en' => 'Last Name', 'hu' => 'Utónév'],
        ]);
        Translation::create([
            'group' => 'order',
            'key' => 'country',
            'text' => ['en' => 'Country', 'hu' => 'Ország'],
        ]);
        Translation::create([
            'group' => 'order',
            'key' => 'city',
            'text' => ['en' => 'City', 'hu' => 'Város'],
        ]);
        Translation::create([
            'group' => 'order',
            'key' => 'zip_code',
            'text' => ['en' => 'Zip Code', 'hu' => 'Irányítószám'],
        ]);
        Translation::create([
            'group' => 'order',
            'key' => 'address',
            'text' => ['en' => 'Address', 'hu' => 'Közterület neve'],
        ]);
        Translation::create([
            'group' => 'order',
            'key' => 'house_nr',
            'text' => ['en' => 'House Number', 'hu' => 'Házszám'],
        ]);
        Translation::create([
            'group' => 'order',
            'key' => 'floor',
            'text' => ['en' => 'Floor', 'hu' => 'Emelet'],
        ]);
        Translation::create([
            'group' => 'order',
            'key' => 'door',
            'text' => ['en' => 'Door', 'hu' => 'Ajtó'],
        ]);
        Translation::create([
            'group' => 'order',
            'key' => 'billing_vat_number',
            'text' => ['en' => 'Billing Vat Number', 'hu' => 'Adószám'],
        ]);
        Translation::create([
            'group' => 'order',
            'key' => 'different_billing',
            'text' => ['en' => 'Different Billing Address', 'hu' => 'Eltérő Számlázási Adatok'],
        ]);

    }

    protected function addShopRelatedTranslations() {
        Translation::create([
            'group' => 'actions',
            'key' => 'add_to_cart',
            'text' => ['en' => 'Add To Cart', 'hu' => 'Kosárba Rakás'],
        ]);
        Translation::create([
            'group' => 'shop',
            'key' => 'shop',
            'text' => ['en' => 'Shop', 'hu' => 'Üzlet'],
        ]);
       
        Translation::create([
            'group' => 'shop',
            'key' => 'products',
            'text' => ['en' => 'Products', 'hu' => 'Termékek'],
        ]);
        Translation::create([
            'group' => 'shop',
            'key' => 'categories',
            'text' => ['en' => 'Categories', 'hu' => 'Kategóriák'],
        ]);
    }
}
