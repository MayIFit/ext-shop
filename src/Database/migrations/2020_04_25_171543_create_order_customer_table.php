<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_customer', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->references('id')->on('orders');
            $table->unsignedBigInteger('customer_id')->references('id')->on('customers');
            $table->boolean('billing');
            $table->primary(['order_id', 'customer_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_customer');
    }
}
