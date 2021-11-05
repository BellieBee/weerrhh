<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdendasContrato extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adendas', function (Blueprint $table) 
        {
            $table->increments('id');            
            $table->date('fecha_contrato')->nullable(); 
            $table->date('fecha_contrato_nueva')->nullable();   
            $table->date('fecha_cumplimineto')->nullable(); 
            $table->date('fecha_cumplimiento_nueva')->nullable();            
            $table->string('motivo')->nullable();            
            
            $table->unsignedInteger('contrato_id');
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
        Schema::drop('adendas');
    }
}
