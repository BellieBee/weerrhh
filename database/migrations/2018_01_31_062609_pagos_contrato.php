<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PagosContrato extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos_contratos', function (Blueprint $table) 
        {
            $table->increments('id');
            
            $table->decimal('monto', 12, 2)->default('0'); 
            $table->string('monto_l')->default('cero');           
            $table->string('monto_producto')->default('');
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
        Schema::drop('pagos_contratos');
    }
}
