<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanillasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planillas', function(Blueprint $table) 
        {
            $table->increments('id'); 
            
            $table->boolean('aprobacion_coordinadora')->default(0);
            $table->date('fecha_aprobacion_coordinadora')->nullable();;
            
            $table->boolean('aprobacion_directora')->default(0);
            $table->date('fecha_aprobacion_directora')->nullable();            
                     
                    
            $table->string('m_a');           
            
            $table->string('porcentaje_pension_pais')->nullable();            
            $table->string('porcentaje_seguridad_social_pais')->nullable();

            $table->string('campo_deducciones')->nullable();
            $table->boolean('confirmada')->default(0);
            $table->decimal('cambio', 12, 2)->default('0'); 
            $table->unsignedInteger('oficina_id');
            $table->unsignedInteger('administradora_id')->nullable();            
            $table->unsignedInteger('coordinadora_id')->nullable();
            $table->unsignedInteger('directora_id')->nullable();;
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
        Schema::drop('planillas');
    }
}
