<?php

use Illuminate\Database\Seeder;

class InsumosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('insumos')->delete();

        Vanguard\Insumo::create([
        	'id' => 1,
        	'insumo' => 'Café'
        ]);

        Vanguard\Insumo::create([
        	'id' => 2,
        	'isumo'=> 'Galletas'
        ]);

        Vanguard\Insumo::create([
        	'id' => 3,
        	'insumo' => 'Té'
        ]);

        Vanguard\Insumo::create([
        	'id' => 4,
        	'insumo' => 'Pasapalos salados'
        ]);
    }
}
