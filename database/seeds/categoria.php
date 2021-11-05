<?php

use Illuminate\Database\Seeder;

class categoria extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		
		DB::table('categorias')->delete();
		Vanguard\Categoria::create( [
		'id'=>'1',
		'categoria'=>'Fijo'
		] );


					
		Vanguard\Categoria::create( [
		'id'=>'2',
		'categoria'=>'Temporal'
		] );


					
		Vanguard\Categoria::create( [
		'id'=>'3',
		'categoria'=>'Consultor'
		] );
    }
}
