<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DefinicionesUyPController extends Controller
{
    public function index(){
//$pass=bcrypt('12345678');
       // dd($pass);
        //get roles
        $roles= DB::table('sys_roles')->where('activo',1)->get();
        //dd($roles);
        //get controladores
        $rutaController = $this->corregirRuta(base_path('app/Http/Controllers'),'');
        $archivos = scandir($rutaController);
    
        $this->insertarFuncion('','');
       return view('testuyp',compact('roles','archivos'));




    }


public function corregirRuta($ruta, $archivo){
    // Se colocan los separadores de directorio correspondientes al sistema operativo
    $ruta_corregida = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $ruta);
    if($archivo != ""){
    $ruta=  $ruta_corregida.DIRECTORY_SEPARATOR.$archivo ;
    }
    // Se escapan los caracteres especiales para que no se formen \n, \t, \r
    $ruta = htmlspecialchars($ruta, ENT_QUOTES, 'UTF-8');
    return $ruta;
}


    public function test($ruta)
    {
       // $archivo = "c:\\Users\\mjomiak\\Desktop\\sigma\\routes\\web.php";
       $archivo = $ruta;

       
       $ruta= base_path('routes');
       $separadorDirectorios = DIRECTORY_SEPARATOR;
     // Ruta con separadores incorrectos (por ejemplo, en Windows)
$ruta = base_path('routes');

// Reemplazar separadores incorrectos por DIRECTORY_SEPARATOR
$ruta_corregida = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $ruta);

$ruta=  $ruta_corregida.DIRECTORY_SEPARATOR.'web.php';

$ruta = htmlspecialchars($ruta, ENT_QUOTES, 'UTF-8');

     echo $ruta;  
     
      $this->test($ruta);

// Línea que deseas agregar
$linea = "Esta es la nueva línea que quiero agregar al final del archivo.\n";

// Abre el archivo en modo de escritura al final del archivo (si no existe, lo crea)
$archivoAbierto = fopen($archivo, 'a');

if ($archivoAbierto) {
    // Escribe la línea al archivo
    fwrite($archivoAbierto, $linea);
    
    // Cierra el archivo
    fclose($archivoAbierto);

    echo "Línea agregada exitosamente.";
} else {
    echo "No se pudo abrir el archivo.";
}










        // Línea que deseas agregar
      //  $linea = "//esta seria una la ruta agregada.\n route:get(ruta ficticia)->name(foo)";
        
        // Abre el archivo en modo de escritura (si no existe, lo crea)
        //file_put_contents($archivo, $linea, FILE_APPEND);
        
      //  echo "Línea agregada exitosamente.";
        
        
            }






 

    public function create(){


//agregar la ruta en el archivo de rutas
$archivo = 'web.php';

// Línea que deseas agregar
$linea = "//esta seria una la ruta agregada.\n route:get(ruta ficticia)->name(foo)";

// Abre el archivo en modo de escritura (si no existe, lo crea)
file_put_contents($archivo, $linea, FILE_APPEND);

echo "Línea agregada exitosamente.";


    }



    function insertarFuncion($archivo,  $textoAInsertar) {
        $textoAInsertar =  PHP_EOL .' public function foo(){'.PHP_EOL.'}'. PHP_EOL .' }';
        $archivo='C:\\Users\\mjomiak\\Desktop\\sigma\\app\\Http\\Controllers\\fooController.php';
       
        $archivo_handle = fopen($archivo, 'r+');
        $caracter = '}';
        if ($archivo_handle) {
            $posicion_ultima_ocurrencia = false;
            while (($linea = fgets($archivo_handle)) !== false) {
                $posicion = strrpos($linea, $caracter);
                if ($posicion !== false) {
                    $posicion_ultima_ocurrencia = ftell($archivo_handle) - strlen($linea) + $posicion;
                }
            }
    
            if ($posicion_ultima_ocurrencia !== false) {
                fseek($archivo_handle, $posicion_ultima_ocurrencia - 1); // Mover el cursor justo después del carácter
            }
    
            fwrite($archivo_handle, $textoAInsertar);
            fclose($archivo_handle);
        } else {
            echo "No se pudo abrir el archivo.";
        }
    }
    
    // Uso de la función
   
    


}
