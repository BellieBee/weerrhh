<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UploadDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('upload_documentos', function (Blueprint $table) 
        {
            $table->increments('id');            
            $table->string('nombre')->nullable(); 
            $table->string('documento')->nullable();           
            $table->unsignedInteger('contrato_id')->nullable();
            $table->unsignedInteger('recepcion_id')->nullable();
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
        Schema::drop('upload_documentos');
    }
}
