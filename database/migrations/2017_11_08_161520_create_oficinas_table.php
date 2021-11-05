<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOficinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oficinas', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('oficina')->unique();
            $table->unsignedInteger('pais_id');
            $table->boolean('central');
            $table->string('direccion')->nullable();
            $table->string('telf')->nullable();
            $table->string('nit')->nullable();
            $table->string('num_patronal')->nullable();
            $table->string('num_h_permiso')->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oficinas');
    }
}
