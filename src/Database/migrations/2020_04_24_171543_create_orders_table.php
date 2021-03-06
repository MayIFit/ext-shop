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
            $table->string('order_id_prefix');
            $table->string('token')->unique();
            $table->foreignId('order_status_id')->references('id')->on('order_statuses');
            $table->foreignId('reseller_id')->nullable()->references('id')->on('resellers');
            $table->foreignId('billing_address_id')->nullable()->references('id')->on('customers');
            $table->foreignId('shipping_address_id')->nullable()->references('id')->on('customers');
            $table->timestamp('placed')->useCurrent();
            $table->unsignedInteger('quantity');
            $table->double('net_value');
            $table->double('gross_value');
            $table->string('currency');
            $table->string('payment_type');
            $table->unsignedSmallInteger('delivery_type');
            $table->double('discount_percentage');
            $table->string('extra_information')->nullable();
            $table->boolean('paid');
            $table->timestamp('sent_to_courier_service')->nullable();
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
