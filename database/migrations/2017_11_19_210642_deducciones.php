<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Deducciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deducciones', function(Blueprint $table)
        {
            $table->increments('id');
            //deducciones toda las oficina
            $table->decimal('prestamo', 12,2)->default('0');
            $table->decimal('interes', 12,2)->default('0');
            $table->decimal('otras_deducciones', 12,2)->default('0');
                       
            $table->decimal('impuesto_renta', 12,2)->default('0');
            $table->decimal('seguridad_social', 12,2)->default('0');

            //Bolivia
            $table->decimal('cta_ind', 12,2)->default('0');
            $table->decimal('riesgo', 12,2)->default('0');
            $table->decimal('com_afp', 12,2)->default('0');
            $table->decimal('afp_aporte_solidario', 12,2)->default('0');
            $table->decimal('afp_aporte_nacional_solidario', 12,2)->default('0');
            $table->decimal('rc_iva', 12,2)->default('0');
            
            //El salvador
            $table->decimal('afp', 12,2)->default('0');
            
            //Honduras
            $table->decimal('seguro_medico', 12,2)->default('0');
            $table->decimal('rap', 12,2)->default('0');

            //Nicaragua
            $table->decimal('deduccion_1', 12,2)->default('0');
            $table->decimal('deduccion_2', 12,2)->default('0');

            $table->decimal('total_deducciones', 12,2)->default('0');
            $table->unsignedInteger('empleado_id'); 
            $table->unsignedInteger('planilla_id'); 
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('deducciones');
    }
}
