<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paises', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('pais')->unique();

            //colores para feriados
            $table->string('color');

            $table->string('moneda_simbolo');
            $table->string('moneda_nombre');

            $table->decimal('porcentaje_seguridad_social',12,2); 
            $table->string('tipo_seguridad_social');

            //seguridad_social_patronal
            $table->decimal('seguridad_social_p',12,2);
            $table->string('tipo_seguridad_social_p');

            //permisos
            $table->integer('n_horas')->default('1');
            $table->integer('n_dias')->default('5');

            //vacaciones
            $table->decimal('vacaciones',12,2)->default('1.25');

            $table->decimal('porcentaje_pension',12,2);

            $table->string('pago_indemnizacion');
            $table->string('pago_pension');
            $table->string('campo_deducciones');           
                       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('paises');
    }
}
