<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DocumentosRecepcion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_recepcion', function (Blueprint $table) 
        {
            $table->increments('id');                    
            $table->string('titulo')->nullable();         
            $table->string('tipo')->nullable();         
            $table->string('prioridad')->nullable();         
            $table->string('descripcion')->nullable();            
            $table->unsignedInteger('recepcion_id');
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
        Schema::drop('documentos_recepcion');
    }
}
