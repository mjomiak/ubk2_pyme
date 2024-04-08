<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MigasFacade extends Facade

{
    protected static function getFacadeAccessor()
    {
        return 'migas'; // Nombre del contenedor de servicio
    }
    
}