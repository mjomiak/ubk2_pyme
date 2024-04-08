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
        Schema::dropIfExists('sys_mnu_func');
        Schema::create('sys_mnu_func', function (Blueprint $table) {
            $table->id(); 
            $table->string('cod_menu',20)->nullable(false)->default('#');
            $table->string('cod_padre',20)->nullable(false)->defult('#'); 
            $table->string('nombre',60)->nullable(false); 
            $table->string('url', 256)->nullable(false);
            $table->string('puerta',30)->nullable(false); 
            $table->string('descrip',256)->nullable(true); 
            $table->string('grupo',40)->nullable(true);
            $table->integer('orden')->default(0); 
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
        Schema::dropIfExists('sys_mnu_func');
    }
};



