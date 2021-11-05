<?php

use Vanguard\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();
        $admin1 = new \Vanguard\Role();
        $admin1->name = 'Admin';
        $admin1->display_name = 'Admin'; // optional
        
        $admin1->save();
         
        $admin2 = new \Vanguard\Role();
        $admin2->name = 'Colega';
        $admin2->display_name = 'Colega'; // optional        
        $admin2->save();
        
        $admin3 = new \Vanguard\Role();
        $admin3->name = 'Administradora';
        $admin3->display_name = 'Administradora'; // optional        
        $admin3->save();
         
        $admin4 = new \Vanguard\Role();
        $admin4->name = 'Coordinadora';
        $admin4->display_name = 'Coordinadora'; // optional        
        $admin4->save();        
         
        $admin5 = new \Vanguard\Role();
        $admin5->name = 'Directora';
        $admin5->display_name = 'Directora'; // optional        
        $admin5->save();

        $admin6 = new \Vanguard\Role();
        $admin6->name = 'Contralora';
        $admin6->display_name = 'Contralora'; // optional        
        $admin6->save();

        /*Role::create([
            'name' => 'Admin',
            'display_name' => 'Admin',
            'description' => 'System administrator.',
            'removable' => false
        ]);

        Role::create([
            'name' => 'User',
            'display_name' => 'User',
            'description' => 'Default system user.',
            'removable' => false
        ]);*/
    }
}
