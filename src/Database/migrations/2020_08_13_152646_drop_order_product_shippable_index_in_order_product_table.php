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
                $table->renameIndex('order_product_shipped_unique', 'order_product_shipped');
                $table->dropIndex('order_product_shippable_unique');
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
                // This name differs for a reason as this type of index is NOT unique
                $table->index(['product_id', 'can_be_shipped'], 'order_product_shippable');
            });
        }
    }
}
