<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NombreCampos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nombre_campos', function(Blueprint $table)
        {
            $table->increments('id');             
            $table->string('salario_base')->default('Salario mensual');
            $table->string('ajustes')->default('Ajustes');
            $table->string('total_salario')->default('Total Salario');
            $table->string('catorceavo')->default('Catorceavo Mes');
            $table->string('prestamo');
            $table->string('interes');
            $table->string('otras_deducciones');
            $table->string('impuestos');            
            $table->string('total_deducciones')->default('Total deducciones');;
            $table->string('seguridad_social');
            $table->string('acumulado_aguinaldo')->default('Provisión aguinaldo 8.33%');
            $table->string('acumulado_indemnizacion')->default('Previsión indemnizacion 8.33%');
            $table->string('seguridad_social_patronal');
            $table->string('liquido');
            $table->unsignedInteger('pais_id');           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('nombre_campos');
    }
}
