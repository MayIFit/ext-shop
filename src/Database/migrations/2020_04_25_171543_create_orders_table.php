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
            $table->unsignedInteger('order_volume');
            $table->double('net_amount');
            $table->double('amount');
            $table->double('discount_percentage');
            $table->double('total_amount');
            $table->string('extra_information');
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
