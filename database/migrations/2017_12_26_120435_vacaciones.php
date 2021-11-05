<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Vacaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('vacaciones', function(Blueprint $table)
        {
            $table->increments('id');            
            
            $table->string('fechas');
            $table->string('num_dh');
            $table->string('dh');           
            $table->unsignedInteger('aprobacion_directora')->default(0);
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
       
        Schema::drop('vacaciones');
    }
}
