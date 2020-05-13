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
        Schema::table('data_types', function (Blueprint $table) {
            $table->string('table_name')->nullable()->after('model_name');
            $table->string('key_name')->nullable()->after('table_name');
            $table->string('key_type')->nullable()->after('key_name');
            $table->string('foreign_key')->nullable()->after('key_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_types', function (Blueprint $table) {
            $table->dropColumn('foreign_key');
            $table->dropColumn('key_type');
            $table->dropColumn('key_name');
            $table->dropColumn('table_name');
        });
    }
}
