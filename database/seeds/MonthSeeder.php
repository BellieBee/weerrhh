<?php

use Illuminate\Database\Seeder;

class MonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('months')->delete();

        Vanguard\Month::create([
        	'id' => 1,
        	'month' => 'Enero'
        ]);

        Vanguard\Month::create([
        	'id' => 2,
        	'month' => 'Febrero'
        ]);

        Vanguard\Month::create([
        	'id' => 3,
        	'month' => 'Marzo'
        ]);

        Vanguard\Month::create([
        	'id' => 4,
        	'month' => 'Abril'
        ]);

        Vanguard\Month::create([
        	'id' => 5,
        	'month' => 'Mayo'
        ]);

        Vanguard\Month::create([
        	'id' => 6,
        	'month' => 'Junio'
        ]);

        Vanguard\Month::create([
        	'id' => 7,
        	'month' => 'Julio'
        ]);

        Vanguard\Month::create([
        	'id' => 8,
        	'month' => 'Agosto'
        ]);

        Vanguard\Month::create([
        	'id' => 9,
        	'month' => 'Septiembre'
        ]);

        Vanguard\Month::create([
        	'id' => 10,
        	'month' => 'Octubre'
        ]);
        Vanguard\Month::create([
        	'id' => 11,
        	'month' => 'Noviembre'
        ]);
        Vanguard\Month::create([
        	'id' => 12,
        	'month' => 'Diciembre'
        ]);
    }
}
