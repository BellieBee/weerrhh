<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('pagos_contratos', function (Blueprint $table) 
        {
            $table->foreign('contrato_id')
            ->references('id')
            ->on('contratos')
            ->onDelete('cascade');
        });
        Schema::table('upload_documentos', function (Blueprint $table) 
        {
            $table->foreign('contrato_id')
            ->references('id')
            ->on('contratos')
            ->onDelete('cascade');
        });

        Schema::table('documentos_recepcion', function (Blueprint $table) 
        {
            $table->foreign('recepcion_id')
            ->references('id')
            ->on('recepciones')
            ->onDelete('cascade');
        });
        /*Schema::table('users', function (Blueprint $table) 
        {
            $table->foreign('oficina_id')
            ->references('id')
            ->on('oficinas')
            ->onDelete('set null');
        });

        Schema::table('users', function (Blueprint $table)
        {
            $table->foreign('cargo_id')
            ->references('id')
            ->on('cargos')
            ->onDelete('set null');
        });

        Schema::table('users', function (Blueprint $table)
        {
            $table->foreign('tipo_documento_id')
            ->references('id')
            ->on('tipo_documentos')
            ->onDelete('set null');
        });

          Schema::table('users', function (Blueprint $table)
        {
            $table->foreign('categoria_id')
            ->references('id')
            ->on('categorias')
            ->onDelete('set null');
        });

           Schema::table('users', function (Blueprint $table)
        {
            $table->foreign('profesion_id')
            ->references('id')
            ->on('profesiones')
            ->onDelete('set null');
        });*/
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       /* Schema::table('users', function(Blueprint $table) {
            $table->dropForeign('users_oficina_id_foreign');
            $table->dropForeign('users_cargo_id_foreign');
            $table->dropForeign('users_tipo_documento_id_foreign');
            $table->dropForeign('users_categoria_id_foreign');
            $table->dropForeign('users_profesion_id_foreign');
            
          
        })*/
        if (Schema::hasTable('pagos_contratos')) {
    //      
            Schema::table('pagos_contratos', function(Blueprint $table) {
                $table->dropForeign('pagos_contratos_contrato_id_foreign');
            });

        }

        if (Schema::hasTable('upload_documentos')) {
    //      
            Schema::table('upload_documentos', function(Blueprint $table) {
                $table->dropForeign('upload_documentos_contrato_id_foreign');
            });

        }

        if (Schema::hasTable('documentos_recepcion')) {
    //      
            Schema::table('documentos_recepcion', function(Blueprint $table) {
                $table->dropForeign('documentos_recepcion_recepcion_id_foreign');
            });

        }
        
    }
}
