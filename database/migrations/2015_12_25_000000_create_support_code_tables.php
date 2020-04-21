<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSupportCodeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // // Create table for storing roles
        // Schema::create('support_code_namespace', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('name')->unique();
        //     $table->timestamps();
        // });
        // // Create table for storing roles
        // Schema::create('support_code_classe_types', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('name')->unique();
        //     $table->timestamps();
        // });

        // Create table for storing roles
        Schema::create('support_code_classes', function (Blueprint $table) {
            $table->string('class_name')->primary()->unique();
            $table->string('filename')->unique();
            $table->string('parent_class')->nullable();
            $table->string('type')->nullable();
            $table->text('data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('data_rows');
        Schema::drop('data_types');
    }
}
