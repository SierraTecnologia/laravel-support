<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for storing roles
        Schema::create('data_relations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table_name');
            $table->string('table_name_inverse');



            $table->string('relation_type');            // MorphMany  morphedByMany   belongsToMany       hasMany
            $table->string('relation_type_inverse');    // morphTo    morphToMany     belongsToMany       belongsTo
            
            $table->string('foreignKey');    //pircingable_id
            $table->string('ownerKey');      // code

            $table->timestamps();

            //$table->foreign('author_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('data_relations');
    }
}
