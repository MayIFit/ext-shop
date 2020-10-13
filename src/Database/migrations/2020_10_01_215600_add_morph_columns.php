<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMorphColumns extends Migration
{

    private $tableList = [
        'products',
        'product_categories',
        'product_discounts',
        'product_category_discounts',
        'product_pricings',
        'reseller_groups',
        'resellers',
        'customers',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->tableList as $t) {
            if (Schema::hasTable($t)) {
                Schema::table($t, function (Blueprint $table) use ($t) {
                    if (!Schema::hasColumns($t, ['created_by_type', 'updated_by_type']) && Schema::hasColumns($t, ['created_by', 'updated_by'])) {
                        $table->dropForeign(['created_by', 'updated_by']);
                        $table->dropColumn(['created_by', 'updated_by']);
                        $table->morphs('created_by');
                        $table->nullableMorphs('updated_by');
                    }
                    if (!Schema::hasColumns($t, ['user_type']) && Schema::hasColumns($t, ['user_id'])) {
                        $table->dropForeign(['user_id']);
                        $table->dropColumn(['user_id']);
                        $table->nullableMorphs('user');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->tableList as $t) {
            if (Schema::hasTable($t)) {
                Schema::table($t, function (Blueprint $table) use ($t) {
                    if (!Schema::hasColumns($t, ['created_by', 'updated_by']) && Schema::hasColumns($t, ['created_by_type', 'updated_by_type'])) {
                        $table->dropMorphs(['created_by', 'updated_by']);
                        $table->foreignId('created_by')->nullable()->references('id')->on('users');
                        $table->foreignId('updated_by')->nullable()->references('id')->on('users');
                    }

                    if (Schema::hasColumns($t, ['user_type'])) {
                        $table->dropMorphs('user');
                        $table->foreignId('user_id')->nullable()->references('id')->on('users');
                    }
                });
            }
        }
    }
}
