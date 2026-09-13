<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPolicyNameToDataTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('data_types') && ! Schema::hasColumn('data_types', 'policy_name')) {
            Schema::table(
                'data_types', function (Blueprint $table) {
                    $table->string('policy_name')->nullable()->after('model_name');
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
        if (Schema::hasTable('data_types') && Schema::hasColumn('data_types', 'policy_name')) {
            Schema::table(
                'data_types', function (Blueprint $table) {
                    $table->dropColumn('policy_name');
                }
            );
        }
    }
}
