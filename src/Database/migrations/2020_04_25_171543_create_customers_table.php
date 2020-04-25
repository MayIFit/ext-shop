<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->references('id')->on('users')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('address')->nullable();
            $table->string('house_nr')->nullable();
            $table->string('floor')->nullable();
            $table->string('door')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->boolean('different_billing')->default(false);
            $table->boolean('company_billing')->default(false);
            $table->string('billing_first_name')->nullable();
            $table->string('billing_last_name')->nullable();
            $table->string('billing_company_name')->nullable();
            $table->string('billing_vat_number')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_zip_code')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_house_nr')->nullable();
            $table->string('billing_floor')->nullable();
            $table->string('billing_door')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
