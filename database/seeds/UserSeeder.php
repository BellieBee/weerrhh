<?php

use Vanguard\Role;
use Vanguard\Support\Enum\UserStatus;
use Vanguard\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        $faker = Faker::create();

        function guardar($correo,$oficina,$role,$faker)
        {
          $user = User::create([
              'first_name'            => $faker->firstName,
              'last_name'             => $faker->lastName,
              'email'                 => $correo,              
              'password'              => '654321',
              'salario_base'          => 13000,
              'oficina_id'            => $oficina,
              'profesion_id'          => '1',
              'cargo_id'              => '1',
              'tipo_documento_id'     => '1',
              'categoria_id'          => '1',
              'avatar'                => null,
              'country_id'            => null,
              'status'                => 1,
              'acumulado_vacaciones'  => $faker->numberBetween($min =3, $max = 16),
            ]);

            $admin = Role::where('name', $role)->first();        
            $user->attachRole($admin);
        } 
        
        
        
        guardar('GTMR@correo.com',1,'Administradora',$faker);           
        guardar('COLE-GTMR@correo.com',1,'Colega',$faker);

        guardar('GTM@correo.com',2,'Administradora',$faker);           
        guardar('COLE-GTM@correo.com',2,'Colega',$faker);

        guardar('BLV@correo.com',3,'Administradora',$faker);            
        guardar('COLE-BLV@correo.com',3,'Colega',$faker);

        guardar('NCR@correo.com',4,'Administradora',$faker);            
        guardar('COLE-NCR@correo.com',4,'Colega',$faker);

        guardar('HDR@correo.com',5,'Administradora',$faker);            
        guardar('COLE-HDR@correo.com',5,'Colega',$faker);

        guardar('HDRUE@correo.com',6,'Administradora',$faker);            
        guardar('COLE-HDRUE@correo.com',6,'Colega',$faker);

        guardar('PRG@correo.com',7,'Administradora',$faker);            
        guardar('COLE-PRG@correo.com',7,'Colega',$faker);

        guardar('SLV@correo.com',8,'Administradora',$faker);            
        guardar('COLE-SLV@correo.com',8,'Colega',$faker);


        guardar('direc@correo.com',1,'Directora',$faker);            
        guardar('contralora@correo.com',1,'Contralora',$faker);            
        guardar('coord@correo.com',1,'Coordinadora',$faker);
        guardar('admin@correo.com',1,'Admin',$faker);            
                   

        





        $roleIds = DB::table('roles')->lists('id');

        //ingreso 100 registros
        foreach ((range(1, 80)) as $index) 
        {
          DB::table('role_user')->insert(
            [
              'role_id' => 2,
              'user_id' => factory(Vanguard\User::class)->create()->id
            ]
          );
         }
    }
}
