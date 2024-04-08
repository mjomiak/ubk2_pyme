<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; 

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


     protected $tabla = 'sys_mainmenu';


    public function up()
    {
       /* Schema::create($this->nombreTabla, function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->timestamps();
        });*/
        DB::table($this->tabla)->truncate();
        // Raiz Sistema
        DB::table($this->tabla)->insert([
            'cod_rol' => 'rootAdmin',
            'cod_menu' => 'sys',
            'cod_padre' => '#',
            'nombre' => 'Sistema',
            'puerta' => 'sys',
          
        ]);
        // Menu sistema -> permisos
        DB::table($this->tabla)->insert([
            'cod_rol' => 'rootAdmin',
            'cod_menu' => 'sys/uyp',
            'cod_padre' => 'sys',
            'nombre' => 'Permisos',
            'puerta' => 'sys/uyp',
            
        ]);
      
    
    // Menu sistema -> usuarios
    DB::table($this->tabla)->insert([
        'cod_rol' => 'rootAdmin',
        'cod_menu' => 'sys/usr',
        'cod_padre' => 'sys',
        'nombre' => 'Usuarios',
        'puerta' => 'sys/usr',
        
    ]);

    // Menu sistema -> roles
    DB::table($this->tabla)->insert([
        'cod_rol' => 'rootAdmin',
        'cod_menu' => 'sys/rol',
        'cod_padre' => 'sys',
        'nombre' => 'Roles',
        'puerta' => 'sys/rol',
        
    ]);
 // Menu sistema -> Parametros
    DB::table($this->tabla)->insert([
        'cod_rol' => 'rootAdmin',
        'cod_menu' => 'sys/prm',
        'cod_padre' => 'sys',
        'nombre' => 'Parámetros',
        'puerta' => 'sys/prm',
        
    ]);
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }





};
