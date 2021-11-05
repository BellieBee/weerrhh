<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalonesReunionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salones_reuniones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('salon');
            $table->unsignedInteger('oficina_id');
            $table->string('cantidad_personas_max');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('salones_reuniones');
    }
}
