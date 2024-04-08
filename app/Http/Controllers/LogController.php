<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;

class LogController extends Controller
{
  

    function UltimasActividadesUser($usuarioBuscado, $diasAtras) {
        $lineasEncontradas = [];
        $directorioLogs=base_path().DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR;
        $fechaActual = Carbon::now();
        $fechaInicio = $fechaActual->copy()->subDays($diasAtras);
    
        while ($fechaInicio <= $fechaActual) {
            $nombreArchivo = 'act-' . $fechaInicio->format('Y-m-d') . '.log';
            $rutaCompleta = $directorioLogs.$nombreArchivo;
    
            if (file_exists($rutaCompleta)) {
                // Lee el contenido del archivo de log
                $contenido = file_get_contents($rutaCompleta);
    
                // Divide el contenido en líneas y verifica cada línea
                $lineas = explode("\n", $contenido);
                foreach ($lineas as $linea) {
                    // Quitar entidades HTML y el salto de línea al final de cada línea
                    $linea = rtrim(html_entity_decode($linea));
    
                    // Verificar si la línea contiene la cadena buscada
                    if (strpos($linea, $usuarioBuscado) !== false) {
                        $lineasEncontradas[] = $linea;
                    }
                }
            }
    
            // Avanzar al siguiente día
            $fechaInicio->addDay();
        }
    
        return $lineasEncontradas;
    }
   
    
}
