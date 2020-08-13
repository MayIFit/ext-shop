<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantityTransferredToOrderProductTable extends Migration
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
                $table->integer('quantity_transferred')->nullable();
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
                $table->dropColumn([
                    'quantity_transferred',
                ]);
            });
        }
    }
}
