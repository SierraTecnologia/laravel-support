<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToDataTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('data_types')) {
            Schema::table(
                'data_types', function (Blueprint $table) {
                    if (! Schema::hasColumn('data_types', 'table_name')) {
                        $table->string('table_name')->nullable()->after('model_name');
                    }
                    if (! Schema::hasColumn('data_types', 'key_name')) {
                        $table->string('key_name')->nullable()->after('table_name');
                    }
                    if (! Schema::hasColumn('data_types', 'key_type')) {
                        $table->string('key_type')->nullable()->after('key_name');
                    }
                    if (! Schema::hasColumn('data_types', 'foreign_key')) {
                        $table->string('foreign_key')->nullable()->after('key_type');
                    }
                }
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('data_types')) {
            Schema::table(
                'data_types', function (Blueprint $table) {
                    foreach (['foreign_key', 'key_type', 'key_name', 'table_name'] as $column) {
                        if (Schema::hasColumn('data_types', $column)) {
                            $table->dropColumn($column);
                        }
                    }
                }
            );
        }
    }
}
