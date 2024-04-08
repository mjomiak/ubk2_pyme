<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UyPController extends Controller
{
    public function VerificarPermisos($username, $cod_rol, $puerta)
    {
        $permitido = DB::table('sys_def_permisos')->where('puerta', $puerta)->where('cod_rol', $cod_rol)->value('permitido');
       
        Log::channel('sistema')->info('Permisos para user:' . $username . ' rol:' . $cod_rol . ' puerta:' . $puerta . ': ' . $permitido);
       // dd('permitido en verificar: '.$permitido);
        return $permitido;
    }



    public function getRutaFunc($puerta)
    {
        $ruta = DB::table('sys_rutas_puertas')->where('puerta', $puerta)->value('ruta');
        Log::channel('sistema')->info('resultado consulta: ' . $ruta);
        if ($ruta === null) {
            Log::channel('sistema')->info('Revisar registro para la ' . $puerta . ' en la tabla SYS_RUTAS_PUERTAS');
            return '#';
        } else {
            Log::channel('sistema')->info('devuelvo la ruta: ' . $ruta . ' para la puerta: ' . $puerta);
            return $ruta;
        }
    }


    public function GetMeRuta($username, $cod_rol, $puerta)
    {
        $permitido = $this->VerificarPermisos($username, $cod_rol, $puerta);
        Log::channel('sistema')->info('en germeruta: '.$username.'  '. $cod_rol.' '. $puerta.' '. $permitido);
      //  dd($permitido, $puerta);
        if ($permitido==1) {
            return $this->getRutaFunc($puerta);
        } else {
            return '#';
        }
    }
}
