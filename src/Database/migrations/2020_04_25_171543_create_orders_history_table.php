<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->references('id')->on('orders');
            $table->unsignedBigInteger('customer_id')->references('id')->on('customers');
            $table->string('order_token');
            $table->string('order_status')->default('placed');
            $table->timestamp('order_placed');
            $table->unsignedInteger('order_quantity');
            $table->double('net_value');
            $table->double('value');
            $table->double('discount_percentage');
            $table->double('total_value');
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
        Schema::dropIfExists('orders_history');
    }
}
