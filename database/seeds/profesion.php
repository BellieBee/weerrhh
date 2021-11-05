<?php

use Illuminate\Database\Seeder;

class profesion extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('profesiones')->delete();
		Vanguard\Profesion::create( [
		'id'=>1,
		'profesion'=>'Administrador RH'
		] );
					
		Vanguard\Profesion::create( [
		'id'=>2,
		'profesion'=>'Ingeniero'
		] );
					
		Vanguard\Profesion::create( [
		'id'=>3,
		'profesion'=>'Abogado'
		] );
					
		Vanguard\Profesion::create( [
		'id'=>4,
		'profesion'=>'Administrador'
		] );
					
		Vanguard\Profesion::create( [
		'id'=>5,
		'profesion'=>'Asesor'
		] );
					
		Vanguard\Profesion::create( [
		'id'=>6,
		'profesion'=>'Atleta'
		] );
					
		Vanguard\Profesion::create( [
		'id'=>7,
		'profesion'=>'Auxiliar'
		] );
					
		Vanguard\Profesion::create( [
		'id'=>8,
		'profesion'=>'Cardiólogo'
		] );
					
		Vanguard\Profesion::create( [
		'id'=>9,
		'profesion'=>'Codirector'
		] );
					
		Vanguard\Profesion::create( [
		'id'=>10,
		'profesion'=>'Decano'
		] );
					
		Vanguard\Profesion::create( [
		'id'=>11,
		'profesion'=>'Doctor'
		] );
					
		Vanguard\Profesion::create( [
		'id'=>12,
		'profesion'=>'Educador'
		] );
					
		Vanguard\Profesion::create( [
		'id'=>13,
		'profesion'=>'Economista'
		] );
					
		Vanguard\Profesion::create( [
		'id'=>14,
		'profesion'=>'Estadístico'
		] );
					
		Vanguard\Profesion::create( [
		'id'=>15,
		'profesion'=>'Farmacéutico'
		] );
					
		Vanguard\Profesion::create( [
		'id'=>16,
		'profesion'=>'Fisiólogo'
		] );
					
		Vanguard\Profesion::create( [
		'id'=>17,
		'profesion'=>'Fonólogo'
		] );
					
		Vanguard\Profesion::create( [
		'id'=>18,
		'profesion'=>'Geofísico'
		] );
					
		Vanguard\Profesion::create( [
		'id'=>19,
		'profesion'=>'Geógrafo'
		] );
					
		Vanguard\Profesion::create( [
		'id'=>20,
		'profesion'=>'Informático'
		] );
		Vanguard\Profesion::create( [
		'id'=>21,
		'profesion'=>'Sin Profesion'
		] );
    }
}
