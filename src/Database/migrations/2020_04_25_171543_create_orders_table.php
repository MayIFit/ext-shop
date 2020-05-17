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
            $table->unsignedBigInteger('customer_id')->nullable()->references('id')->on('customers');
            $table->string('token')->unique();
            $table->foreignId('order_status_id')->references('id')->on('order_statuses');
            $table->timestamp('placed')->useCurrent();
            $table->unsignedInteger('quantity');
            $table->double('net_value');
            $table->double('gross_value');
            $table->double('discount_percentage');
            $table->string('extra_information')->nullable();
            $table->boolean('paid');
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
