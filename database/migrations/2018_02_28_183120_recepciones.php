<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Recepciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recepciones', function (Blueprint $table) 
        {
            $table->increments('id');                    
            $table->date('fecha_recibe')->nullable();         
            $table->boolean('recogido')->default(0);           
            $table->unsignedInteger('oficina_id');
            $table->unsignedInteger('user_id');
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
        Schema::drop('recepciones');
    }
}
