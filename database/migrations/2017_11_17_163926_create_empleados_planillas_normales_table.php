<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpleadosPlanillasNormalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados_planillas_normales', function(Blueprint $table)
        {
            $table->increments('id');            
            $table->string('nombre');
            $table->string('n_contrato');
            $table->string('documento');
            $table->string('cargo');
            $table->string('fecha_inicio');
            $table->integer('dias_trabajados')->default('0');
            $table->decimal('salario_base', 12, 2);
            $table->decimal('ajuste', 12, 2)->default('0');            
            $table->decimal('total_salario', 12, 2)->default('0'); 
            $table->decimal('liquido_recibir', 12, 2)->default('0');

            //aguinaldo
            $table->decimal('total_aguinaldo', 10, 2)->nullable();
            $table->decimal('total_pension', 10, 2)->nullable();
            $table->decimal('total_indemnizacion', 10, 2)->nullable();          
            $table->unsignedInteger('planilla_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();          
            
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('empleados_planillas_normales');
    }
}
