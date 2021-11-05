<?php

use Illuminate\Database\Seeder;

class oficina extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oficinas')->delete();
        Vanguard\Oficina::create( [
		'id'=>1,
		'oficina'=>'Guatemala Regional',
		'pais_id'=>1,
		'central'=>1,
		'direccion'=>'15 avenida \"A\" 5-00, zona 13, Guatemala.      ',
		'telf'=>'+502 2216-8400',
		'nit'=>'',
		'num_patronal'=>'',
		'num_h_permiso'=>NULL
		] );
					
		Vanguard\Oficina::create( [
		'id'=>2,
		'oficina'=>'Guatemala',
		'pais_id'=>1,
		'central'=>0,
		'direccion'=>' 15 avenida \"A\" 5-00, zona 13, Guatemala.      ',
		'telf'=>'+502 2216-8400',
		'nit'=>'',
		'num_patronal'=>'',
		'num_h_permiso'=>NULL
		] );
					
		Vanguard\Oficina::create( [
		'id'=>3,
		'oficina'=>'Bolivia',
		'pais_id'=>2,
		'central'=>0,
		'direccion'=>'Calle Ecuador Nº 183  entre Ayacucho y Junin. Cochabamba, Bolivia    ',
		'telf'=>'+591  4029249',
		'nit'=>'',
		'num_patronal'=>'',
		'num_h_permiso'=>NULL
		] );
					
		Vanguard\Oficina::create( [
		'id'=>4,
		'oficina'=>'Nicaragua',
		'pais_id'=>3,
		'central'=>0,
		'direccion'=>'De la CST 520vr hacia el sur, mano derecha, Managua, Nicaragua    ',
		'telf'=>'(505) 2268 6201 (505) 2264 0085',
		'nit'=>'',
		'num_patronal'=>'',
		'num_h_permiso'=>NULL
		] );
					
		Vanguard\Oficina::create( [
		'id'=>5,
		'oficina'=>'Honduras',
		'pais_id'=>4,
		'central'=>0,
		'direccion'=>'Colonia Lomas de Miraflores Sur, Tercera Calle, Bloque D13 Tegucigalpa, Honduras    ',
		'telf'=>'(504) 2271-1054 (504) 2271-1053 (504) 2271-1051',
		'nit'=>'',
		'num_patronal'=>'',
		'num_h_permiso'=>NULL
		] );

		Vanguard\Oficina::create( [
		'id'=>6,
		'oficina'=>'Honduras UE',
		'pais_id'=>4,
		'central'=>0,
		'direccion'=>'Colonia Lomas de Miraflores Sur, Tercera Calle, Bloque D13 Tegucigalpa, Honduras    ',
		'telf'=>'(504) 2271-1054 (504) 2271-1053 (504) 2271-1051',
		'nit'=>'',
		'num_patronal'=>'',
		'num_h_permiso'=>NULL
		] );
					
		Vanguard\Oficina::create( [
		'id'=>7,
		'oficina'=>'Paraguay',
		'pais_id'=>5,
		'central'=>0,
		'direccion'=>'Independencia Nacional \r\nN° 349 c/ Palma, Edif. Independencia           \r\nPiso 5 Of 501\r\nAsuncion Paraguay    ',
		'telf'=>'',
		'nit'=>'',
		'num_patronal'=>'',
		'num_h_permiso'=>NULL
		] );
					
		Vanguard\Oficina::create( [
		'id'=>8,
		'oficina'=>'El Salvador',
		'pais_id'=>6,
		'central'=>0,
		'direccion'=>'Altos de Miralvalle Poniente, Calle Atenas, Casa #2 Polígono G \r\nSan Salvador.         ',
		'telf'=>'(503) 2284 0408',
		'nit'=>'',
		'num_patronal'=>'',
		'num_h_permiso'=>NULL
		] );
    }
}
