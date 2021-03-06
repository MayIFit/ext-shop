<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCanBeShippedColumnInOrderProductTable extends Migration
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
                $table->dropColumn('can_be_shipped');
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
                $table->boolean('can_be_shipped');
            });
        }
    }
}
