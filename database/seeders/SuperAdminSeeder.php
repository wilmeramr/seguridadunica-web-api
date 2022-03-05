<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usuario = User::create([
            'name'=> 'Super Admin',
            'email' => 'superadmin@seguridadunica.com',
            'password' => bcrypt('12345678'),
            'country_id' => 0
        ]);

     //   $rol = Role::create(['name'=>'Administrador']);

        //$permisos = Permission::pluck('id','id')->all();
       // $rol->syncPermissions($permisos);
        $usuario->assignRole('Administrador');

    }
}
