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
        Schema::create(
            'support_code_classers', function (Blueprint $table) {
                $table->string('class_name')->primary()->unique();
                $table->string('filename')->unique();
                $table->string('parent_class')->nullable();
                $table->string('type')->nullable();
                $table->text('data')->nullable();
            }
        );
        // Create table for storing roles
        Schema::create(
            'support_code_entitys', function (Blueprint $table) {
                $table->string('code')->primary()->unique();
                $table->string('type')->nullable();
                $table->string('parameter')->nullable();
                $table->string('md5')->nullable();
                $table->text('data')->nullable();
                $table->string('support_code_entity_code')->nullable();

                $table->timestamps();
            }
        );
        // Create table for storing roles
        Schema::create(
            'support_code_errors', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();

                $table->string('target')->nullable();
                $table->string('class_type')->nullable();
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
        Schema::drop('support_code_errors');
        Schema::drop('support_code_entitys');
        Schema::drop('support_code_classers');
    }
}
