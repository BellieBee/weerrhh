<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos', function (Blueprint $table) 
        {
            $table->increments('id');
            $table->string('n_contrato');            
            $table->string('consultoria');
            $table->string('objetivo');
            $table->string('alcance');
            $table->string('actividades');
            $table->string('metodologia');
            $table->date('fecha_contrato');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('tiempo_contrato');
            $table->string('monto_total');           
            $table->string('monto_total_l');           
            $table->string('productos');           
            $table->string('cumplimiento')->nullable();
            $table->unsignedInteger('status')->default('0');
            $table->string('aprobacion_coordinadora')->default('0');
            $table->date('fecha_aprobacion_coordinadora');
            $table->string('aprobacion_directora')->default('0');
            $table->date('fecha_aprobacion_directora');
            $table->unsignedInteger('coordinadora_id')->nullable();
            $table->unsignedInteger('directora_id')->nullable();
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
        Schema::drop('contratos');
    }
}
