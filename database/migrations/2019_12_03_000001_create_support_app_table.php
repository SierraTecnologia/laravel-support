<?php
/**
 * Migrations para Manipulação de Erros
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportAppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for storing roles
        Schema::create(
            'support_app_systems', function (Blueprint $table) {
                $table->string('code')->primary()->unique();
                $table->string('md5')->nullable();
                $table->text('data')->nullable();

                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('support_app_systems');
    }
}
