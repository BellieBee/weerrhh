<?php

use Illuminate\Database\Seeder;

class SalonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('salones_reuniones')->delete();

        Vanguard\Salon::create([
        	'id' => 1,
        	'salon' => 'A1',
        	'oficina_id' => 1,
        	'cantidad_personas_max' => 20
        ]);

		Vanguard\Salon::create([
        	'id' => 2,
        	'salon' => 'B1',
        	'oficina_id' => 2,
        	'cantidad_personas_max' => 10
        ]);

        Vanguard\Salon::create([
        	'id' => 3,
        	'salon' => 'C1',
        	'oficina_id' => 3,
        	'cantidad_personas_max' => 10
        ]);

        Vanguard\Salon::create([
        	'id' => 4,
        	'salon' => 'D1',
        	'oficina_id' => 4,
        	'cantidad_personas_max' => 10
        ]);

        Vanguard\Salon::create([
        	'id' => 5,
        	'salon' => 'E1',
        	'oficina_id' => 5,
        	'cantidad_personas_max' => 10
        ]);

        Vanguard\Salon::create([
        	'id' => 6,
        	'salon' => 'F1',
        	'oficina_id' => 6,
        	'cantidad_personas_max' => 10
        ]);

        Vanguard\Salon::create([
        	'id' => 7,
        	'salon' => 'G1',
        	'oficina_id' => 7,
        	'cantidad_personas_max' => 10
        ]);

        Vanguard\Salon::create([
        	'id' => 8,
        	'salon' => 'A1',
        	'oficina_id' => 8,
        	'cantidad_personas_max' => 10
        ]);

        Vanguard\Salon::create([
        	'id' => 9,
        	'salon' => 'A2',
        	'oficina_id' => 1,
        	'cantidad_personas_max' => 10
        ]);		        
    }
}
