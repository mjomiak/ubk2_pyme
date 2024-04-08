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
        Schema::dropIfExists('sys_roles');
        Schema::create('sys_roles', function (Blueprint $table) {
            $table->id(); 
            $table->string('cod_rol', 30)->nullable(false);
            $table->string('nombre')->nullable(false); 
            $table->string('descrip')->nullable(); 
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
        Schema::dropIfExists('sys_roles');
    }
};


//---

