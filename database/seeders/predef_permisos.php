<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class predef_permisos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // Ejemplo de inserción de datos en la tabla
         DB::table('sys_def_permisos')->truncate();
         DB::table('sys_def_permisos')->insert([
           'puerta'     =>'usr/view',
           'permitido'  =>true,
           'cod_rol'    =>'rootAdmin',
           'nombre'     =>'Listar Usuarios',
           'activo'     =>1,
           'accion'     =>0,
           'borrado'    =>false,
           'flag_aux'   =>0,
           'created_at' => now(),
           'updated_at' => now(),
        ]);

        DB::table('sys_def_permisos')->insert([
            'puerta'     =>'usr/create',
            'permitido'  =>true,
            'cod_rol'    =>'rootAdmin',
            'nombre'     =>'Crear Usuarios',
            'activo'     =>1,
            'accion'     =>0,
            'borrado'    =>false,
            'flag_aux'   =>0,
            'created_at' => now(),
            'updated_at' => now(),
         ]);

         DB::table('sys_def_permisos')->insert([
            'puerta'     =>'usr/modif',
            'permitido'  =>true,
            'cod_rol'    =>'rootAdmin',
            'nombre'     =>'Crear Usuarios',
            'activo'     =>1,
            'accion'     =>0,
            'borrado'    =>false,
            'flag_aux'   =>0,
            'created_at' => now(),
            'updated_at' => now(),
         ]);

         DB::table('sys_def_permisos')->insert([
            'puerta'     =>'usr/view',
            'permitido'  =>true,
            'cod_rol'    =>'sysAdmin',
            'nombre'     =>'Listar Usuarios',
            'activo'     =>1,
            'accion'     =>0,
            'borrado'    =>false,
            'flag_aux'   =>0,
            'created_at' => now(),
            'updated_at' => now(),
         ]);
 
         DB::table('sys_def_permisos')->insert([
             'puerta'     =>'usr/create',
             'permitido'  =>true,
             'cod_rol'    =>'sysAdmin',
             'nombre'     =>'Crear Usuarios',
             'activo'     =>1,
             'accion'     =>0,
             'borrado'    =>false,
             'flag_aux'   =>0,
             'created_at' => now(),
             'updated_at' => now(),
          ]);
 
          DB::table('sys_def_permisos')->insert([
             'puerta'     =>'usr/modif',
             'permitido'  =>true,
             'cod_rol'    =>'sysAdmin',
             'nombre'     =>'Crear Usuarios',
             'activo'     =>1,
             'accion'     =>0,
             'borrado'    =>false,
             'flag_aux'   =>0,
             'created_at' => now(),
             'updated_at' => now(),
          ]);
    }
}


