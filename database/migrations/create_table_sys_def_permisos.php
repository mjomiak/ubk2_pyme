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
        Schema::dropIfExists('sys_def_permisos');
        Schema::create('sys_def_permisos', function (Blueprint $table) {
            $table->id(); 
            $table->string('puerta', 30)->nullable(false);
            $table->boolean('permitido')->nullable(false)->default(0); 
            $table->string('cod_rol')->nullable(); 
            $table->string('nombre',127)->nullable(false); 
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
        Schema::dropIfExists('sys_def_permisos');
    }
};




