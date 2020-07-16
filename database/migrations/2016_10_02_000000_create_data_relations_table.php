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
        Schema::create(
            'data_relations', function (Blueprint $table) {
                $table->string('code')->primary()->unique();




                $table->string('origin_table_name')->nullable();
                $table->string('origin_table_class')->nullable();
                $table->string('origin_foreignKey')->nullable();
        
                $table->string('related_table_name')->nullable();
                $table->string('related_table_class')->nullable();
                $table->string('related_foreignKey')->nullable();
        
                // Morph
                $table->string('morph_id')->nullable();
                $table->string('morph_type')->nullable();
                $table->string('is_inverse')->nullable();
        
                // Others Values
                $table->string('pivot')->nullable();
        
                $table->string('name')->nullable();
                $table->string('type')->nullable();
                $table->string('model')->nullable();
                $table->string('foreignKey')->nullable();
                $table->string('ownerKey')->nullable();


                // $table->string('table_name');
                // $table->string('table_name_related');


                // $table->string('table_name');
                // $table->string('table_name_inverse');



                // $table->string('relation_type');            // MorphMany  morphedByMany   belongsToMany       hasMany
                // $table->string('relation_type_inverse');    // morphTo    morphToMany     belongsToMany       belongsTo
            
                // $table->string('foreignKey')->nullable();    //pircingable_id
                // $table->string('ownerKey')->nullable();      // code
                // $table->text('data')->nullable();

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
        Schema::drop('data_relations');
    }
}
