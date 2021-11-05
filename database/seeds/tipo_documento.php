<?php

use Illuminate\Database\Seeder;

class tipo_documento extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {	
		DB::table('tipo_documentos')->delete();

        Vanguard\Tipo_documento::create( [
		'id'=>1,
		'tipo_documento'=>'Pasaporte'
		] );


					
		Vanguard\Tipo_documento::create( [
		'id'=>2,
		'tipo_documento'=>'DPI'
		] );


					
		Vanguard\Tipo_documento::create( [
		'id'=>3,
		'tipo_documento'=>'Carnet de Identidad'
		] );


					
		Vanguard\Tipo_documento::create( [
		'id'=>4,
		'tipo_documento'=>'Documento de Identidad'
		] );
    }
}
