<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sys_asoc_rol_user');
        Schema::create('sys_asoc_rol_user', function (Blueprint $table) {
            $table->id(); 
            $table->integer('id_user')->nullable(false);
            $table->string('cod_rol')->nullable(false); 
            $table->boolean('activo')->default(false); 
            $table->smallInteger('accion')->default(0); 
            $table->boolean('borrado')->default(false); 
            $table->smallInteger('flag_aux')->default(0); 
            $table->timestamps(); 
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_asoc_rol_user');
    }
};



