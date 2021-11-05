<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Permisos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permisos', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('tipo');
            $table->string('motivo');
            $table->string('fecha_inicio');
            $table->string('fecha_fin');
            $table->string('num_dh');
            $table->string('dh');           
            $table->unsignedInteger('aprobacion_coordinadora')->default(0);
            // 0->pendiente
            // 1->aprobada
            // 2->rechazada
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('oficina_id');
            $table->timestamps();                       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::drop('permisos');
    }
}
