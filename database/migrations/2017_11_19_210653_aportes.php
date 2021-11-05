
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Aportes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('aportes', function(Blueprint $table)
        {          

            $table->increments('id');

            //aportes guatemala
            $table->decimal('bonificacion_incentivo', 12,2)->default('0');
            $table->decimal('bonificacion_docto_37_2001', 12,2)->default('0');
            $table->decimal('reintegros', 12,2)->default('0');           
            $table->decimal('bonificacion_14', 12,2)->default('0');           
            // catorceavo
            $table->decimal('catorceavo', 12,2)->default('0');           
            
            //aportes patronales BOLIVIA
            $table->decimal('seguro_universitario', 12,2)->default('0'); 
            $table->decimal('afp_prevision', 12,2)->default('0'); 
            $table->decimal('afp_prevision_pnvs', 12,2)->default('0'); 
            $table->decimal('afp_aporte_solidario', 12,2)->default('0');         
            
            $table->decimal('afp_6_75', 12,2)->default('0'); 
            $table->decimal('INATEC', 12,2)->default('0'); 
            $table->decimal('seguridad_social_patronal', 12,2)->default('0'); 
            $table->decimal('total_aporte_25_5', 12,2)->default('0');
            $table->decimal('total_carga_patronal', 12,2)->default('0');

            //aportes patronales HONDURAS
            $table->decimal('rap', 12,2)->default('0');


            $table->decimal('total_aportes', 12,2)->default('0');
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
    Schema::drop('aportes');
      
    }
}
