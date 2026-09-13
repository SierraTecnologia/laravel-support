<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToDatatypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('data_types') && ! Schema::hasColumn('data_types', 'details')) {
            Schema::table(
                'data_types', function (Blueprint $table) {
                    $table->text('details')->nullable()->after('server_side');
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
        if (Schema::hasTable('data_types') && Schema::hasColumn('data_types', 'details')) {
            Schema::table(
                'data_types', function (Blueprint $table) {
                    $table->dropColumn('details');
                }
            );
        }
    }
}
