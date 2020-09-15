<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropOrderProductShippableIndexInOrderProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('order_product')) {
            Schema::table('order_product', function (Blueprint $table) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexesFound = $sm->listTableIndexes('order_product');

                if (array_key_exists("order_product_shipped_unique", $indexesFound)) {
                    $table->renameIndex('order_product_shipped_unique', 'order_product_shipped');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('order_product')) {
            Schema::table('order_product', function (Blueprint $table) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexesFound = $sm->listTableIndexes('order_product');

                if (array_key_exists("order_product_shippable", $indexesFound)) {
                    $table->renameIndex('order_product_shipped', 'order_product_shipped_unique');
                }
            });
        }
    }
}
