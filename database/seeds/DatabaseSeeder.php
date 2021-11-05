<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        
        
        Model::unguard();
       // factory(Vanguard\Planilla::class,100)->create();
        
        $this->call(cargo::class);
        $this->call(pais::class);
        $this->call(profesion::class);        
        $this->call(categoria::class);
        $this->call(tipo_documento::class);
        $this->call(oficina::class);
        $this->call(MonthSeeder::class); 
        $this->call(RolesSeeder::class);
        $this->call(PermissionsSeeder::class);       
        $this->call(motivo_permiso::class);
        $this->call(UserSeeder::class);
         DB::table('planillas')->delete();
         DB::table('deducciones')->delete();
         DB::table('aportes')->delete();
         DB::table('acumulados')->delete();

        //factory(Vanguard\Planilla::class,50)->create();     
        Model::reguard();
        //$this->call(CountriesSeeder::class);
        //factory(Vanguard\User::class,100)->create();


        
        
    }
}
