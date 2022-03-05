<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;

class SeederTablaPermisos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permisos =[
            'ver-rol',
            'crear-rol',
            'editar-rol',
            'borrar-rol',

            'ver-blog',
            'crear-blog',
            'editar-blog',
            'borrar-blog',

            'ver-country',
            'crear-country',
            'editar-country',
            'borrar-country',

            
            'ver-user',
            'crear-user',
            'editar-user',
            'borrar-user',


        ];

        foreach($permisos as $permiso){
            Permission::create(['name'=>$permiso]);
        }
    }
}
