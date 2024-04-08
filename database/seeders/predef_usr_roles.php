<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class predef_usr_roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = 'sys_asoc_rol_user';
        DB::table($table)->truncate();
        DB::table($table)->insert([
            'id_user' => 1,
            'cod_rol' => 'rootAdmin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table($table)->insert([
            'id_user' => 1,
            'cod_rol' => 'sysAdmin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
