<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeriadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feriados', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('dia');
            $table->unsignedInteger('month_id');
            $table->unsignedInteger('pais_id');
            $table->string('descripcion_feriado')->nullable();
            $table->datetime('fecha')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('feriados');
    }
}
