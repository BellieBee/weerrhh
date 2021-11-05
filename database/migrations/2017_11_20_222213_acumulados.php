<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Acumulados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
   {
        Schema::create('acumulados', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('oficina_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('empleado_id');
            $table->unsignedInteger('planilla_id');
            $table->date('year');            
            $table->string('m_a');          
            $table->decimal('catorceavo', 12, 2)->default('0');
            $table->decimal('total_salario', 12, 2)->default('0');
            
            $table->decimal('aguinaldo', 12, 2)->default('0');
            $table->decimal('pension', 12, 2)->default('0');
            $table->decimal('indemnizacion', 12, 2)->default('0');            
            $table->decimal('retiro_trabajador', 12, 2)->default('0');          
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
          Schema::drop('acumulados');
    }
}
