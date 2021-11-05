<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PlanillaKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empleados_planillas_normales', function (Blueprint $table) 
        {
            $table->foreign('planilla_id')
            ->references('id')
            ->on('planillas')
            ->onDelete('cascade');           


        });

        

        Schema::table('deducciones', function (Blueprint $table) 
        {
            $table->foreign('empleado_id')
            ->references('id')
            ->on('empleados_planillas_normales')
            ->onDelete('cascade');
        });

        Schema::table('aportes', function (Blueprint $table) 
        {
            $table->foreign('empleado_id')
            ->references('id')
            ->on('empleados_planillas_normales')
            ->onDelete('cascade');
        });
        Schema::table('acumulados', function (Blueprint $table) 
        {
            $table->foreign('empleado_id')
            ->references('id')
            ->on('empleados_planillas_normales')
            ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('empleados_planillas_normales')) {
    //      
            Schema::table('empleados_planillas_normales', function(Blueprint $table) {
                $table->dropForeign('empleados_planillas_normales_planilla_id_foreign');
            });

        }
        
        if (Schema::hasTable('deducciones')) {
    //      
            Schema::table('deducciones', function(Blueprint $table) {
                $table->dropForeign('deducciones_empleado_id_foreign');
            });

        }
        if (Schema::hasTable('aportes')) {
    //      
            Schema::table('aportes', function(Blueprint $table) {
                $table->dropForeign('aportes_empleado_id_foreign');
            });

        }
        if (Schema::hasTable('acumulados')) {
    //      
            Schema::table('acumulados', function(Blueprint $table) {
                $table->dropForeign('acumulados_empleado_id_foreign');
            });

        }
    }
}
