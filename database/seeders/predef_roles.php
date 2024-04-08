<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class predef_roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table= 'sys_roles';
         DB::table($table)->truncate();
         DB::table($table)->insert([
            'cod_rol' => 'rootAdmin',
            'nombre' => 'Root Admin',
            'descrip' => 'Administrador del sistema Proveedor',
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table($table)->insert([
            'cod_rol' => 'sysAdmin',
            'nombre' => 'Sys Admin',
            'descrip' => 'Administrador del sistema',
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
         ]);

         DB::table($table)->insert([
            'cod_rol' => 'testRole',
            'nombre' => 'Rol de Pruebas',
            'descrip' => 'Rol para pruebas',
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
         ]);

        
    }
}


