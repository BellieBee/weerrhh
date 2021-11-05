<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReunionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reuniones', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('fecha_solicitud');
            $table->unsignedInteger('oficina_id');
            $table->string('descp_evento');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('salon_id');
            $table->string('insumo');
            $table->datetime('fecha');
            $table->string('dia_completo');
            $table->datetime('fecha_desde');
            $table->datetime('fecha_hasta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reuniones');
    }
}
