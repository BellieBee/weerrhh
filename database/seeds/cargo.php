<?php

use Illuminate\Database\Seeder;

class cargo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cargos')->delete();
       Vanguard\Cargo::create( [
		'id'=>1,
		'cargo'=>'Gerente Administrativo'
		] );


					
		Vanguard\Cargo::create( [
		'id'=>2,
		'cargo'=>'Programador Senior'
		] );


					
		Vanguard\Cargo::create( [
		'id'=>3,
		'cargo'=>'Técnico de marketing'
		] );


					
		Vanguard\Cargo::create( [
		'id'=>4,
		'cargo'=>'Jefe de obra '
		] );


					
		Vanguard\Cargo::create( [
		'id'=>5,
		'cargo'=>'Ayudante'
		] );


					
		Vanguard\Cargo::create( [
		'id'=>6,
		'cargo'=>'Técnico de almacén'
		] );


					
		Vanguard\Cargo::create( [
		'id'=>7,
		'cargo'=>'Técnico de sistemas '
		] );


					
		Vanguard\Cargo::create( [
		'id'=>8,
		'cargo'=>'Carpintero'
		] );


					
		Vanguard\Cargo::create( [
		'id'=>9,
		'cargo'=>'Albañil'
		] );


					
		Vanguard\Cargo::create( [
		'id'=>10,
		'cargo'=>'Cerrajero'
		] );


					
		Vanguard\Cargo::create( [
		'id'=>11,
		'cargo'=>'Electricista'
		] );


					
		Vanguard\Cargo::create( [
		'id'=>12,
		'cargo'=>'Fontanero'
		] );


					
		Vanguard\Cargo::create( [
		'id'=>13,
		'cargo'=>'Instalador de tuberías'
		] );


					
		Vanguard\Cargo::create( [
		'id'=>14,
		'cargo'=>'Jardinero'
		] );


					
		Vanguard\Cargo::create( [
		'id'=>15,
		'cargo'=>'Vigilante de seguridad'
		] );


					
		Vanguard\Cargo::create( [
		'id'=>16,
		'cargo'=>'Conserje'
		] );


					
		Vanguard\Cargo::create( [
		'id'=>17,
		'cargo'=>'Inspector de control'
		] );


					
		Vanguard\Cargo::create( [
		'id'=>18,
		'cargo'=>'Enfermero'
		] );


					
		Vanguard\Cargo::create( [
		'id'=>19,
		'cargo'=>'Capataz'
		] );


					
		Vanguard\Cargo::create( [
		'id'=>20,
		'cargo'=>'Agente comercial'
		] );
    	
    	Vanguard\Cargo::create( [
		'id'=>21,
		'cargo'=>'Sin Cargo'
		] );
    }
}
