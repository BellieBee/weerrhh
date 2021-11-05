<?php

use Illuminate\Database\Seeder;

class motivo_permiso extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('motivo_permisos')->delete();
        DB::table('motivo_permisos')->insert([
            ['motivo'=>'Médico'], 
            ['motivo'=>'Colegio'], 
            ['motivo'=>'Hijos'], 
            ['motivo'=>'Enfermedad'], 
            ['motivo'=>'Suspensión']
        ]);
    }
}
