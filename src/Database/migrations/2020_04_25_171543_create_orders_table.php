<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->references('id')->on('customers');
            $table->string('order_token');
            $table->unsignedInteger('order_quantity')->default(0);
            $table->double('net_value')->default(0);
            $table->double('value')->default(0);
            $table->double('discount_percentage')->default(0);
            $table->double('total_amount')->default(0);
            $table->string('extra_information')->nullable();
            $table->boolean('paid')->default(false);
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
        Schema::dropIfExists('orders');
    }
}
