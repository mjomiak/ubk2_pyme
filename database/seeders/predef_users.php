<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class predef_users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table= 'users';
         DB::table($table)->truncate();
         DB::table($table)->insert([
            'name' => 'Mauricio Jomiak',
            'email' => 'mjomiak@gmail.com',
            'password' => Hash::make('951629'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    

        
    }
}


