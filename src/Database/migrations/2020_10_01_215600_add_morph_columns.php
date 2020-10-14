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
                    $sm = Schema::getConnection()->getDoctrineSchemaManager();
                    $indexes = $sm->listTableIndexes($t);

                    if (!Schema::hasColumns($t, ['created_by_type', 'updated_by_type']) && Schema::hasColumns($t, ['created_by', 'updated_by'])) {
                        if (array_key_exists($t . '_created_by_foreign', $indexes)) {
                            $table->dropForeign($t . '_created_by_foreign');
                        }

                        if (array_key_exists($t . '_updated_by_foreign', $indexes)) {
                            $table->dropForeign($t . '_updated_by_foreign');
                        }

                        $table->dropColumn(['created_by', 'updated_by']);
                        $table->morphs('created_by');
                        $table->nullableMorphs('updated_by');
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
                });
            }
        }
    }
}
