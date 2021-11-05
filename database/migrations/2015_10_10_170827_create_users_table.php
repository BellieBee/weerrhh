<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('username')->nullable()->unique();
            $table->string('password');
            $table->string('firma')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('n_contrato')->nullable();
            $table->string('documento')->nullable();
            $table->string('fecha_inicio')->nullable();
            $table->string('fecha_finalizacion')->nullable();
            $table->string('n_afiliacion')->nullable();
            $table->string('n_identificacion_tributaria')->nullable();
            $table->string('regimen_tributario')->nullable();
            $table->integer('edad')->nullable();
            $table->string('contacto_emergencia')->nullable();
            $table->string('tlf_contacto_emergencia')->nullable();
            $table->string('tipo_sangre')->nullable();
            $table->boolean('sexo')->nullable();
            $table->string('cellphone')->nullable();
            $table->string('skype')->nullable();
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->string('address')->default('');
            $table->date('birthday')->nullable();
            $table->decimal('salario_base', 10, 2)->nullable();
            $table->decimal('acumulado_vacaciones', 10, 2)->default(0);            
            $table->unsignedInteger('oficina_id');
            $table->unsignedInteger('cargo_id')->nullable();
            $table->unsignedInteger('tipo_documento_id')->nullable();
            $table->unsignedInteger('categoria_id')->nullable();
            $table->unsignedInteger('profesion_id')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->string('confirmation_token', 60)->nullable();
            $table->boolean('status');
            $table->integer('two_factor_country_code')->nullable();
            $table->integer('two_factor_phone')->nullable();
            $table->text('two_factor_options')->nullable();
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
