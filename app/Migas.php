<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Migas
{
    public function render($puerta = null)
    {
       if($puerta == 'fake'){
        $divwrapper='<div class="container-fluid" style="margin-bottom: 1rem; padding: 10px; background-color: #e9ecef; color:#007bff;  border-bottom: 1px solid rgba(128, 128, 128, 0.8);" >';
        $cierradiv='</div>';
        $elTodo= $divwrapper." Inicio".$cierradiv;
        return $elTodo;
       }else{
        $rutaCompleta = [];
        $cod_rol=Session('CodRol');
        //Log::channel('sistema')->info('cod_rol a buscar: '.$cod_rol);
        //Log::channel('sistema')->info('puerta a buscar: '.$puerta);
        try{
        $cod_menu= DB::table('sys_mainmenu')->where('puerta',$puerta)->where('cod_rol',$cod_rol)->value('cod_menu');
        }catch(\Exception $e){
            Log::channel('sistema')->error('Obteniendo cod_menu'.$e);
        }

        //Log::channel('sistema')->info('menu a buscar: '.$cod_menu);
        $this->construirRuta($cod_menu, $rutaCompleta);
      //  Log::channel('act')->info("-RUTA DE LAS MIGAS>".print_r($rutaCompleta));
       // return implode(' &#x25B6; ', array_reverse($rutaCompleta));
       $lasMigas= implode(' / ', array_reverse($rutaCompleta));
       $divwrapper='<div class="container-fluid" style="margin-bottom: 1rem; padding: 10px; background-color: #e9ecef; color:#007bff;  border-bottom: 1px solid rgba(128, 128, 128, 0.8);" >';
       $cierradiv='</div>';
       $elTodo= $divwrapper.$lasMigas.$cierradiv;
       return $elTodo;
    }
       
    }

    public function getRutaByPuerta($puerta)
    {
        $ruta = DB::table('sys_rutas_puertas')->where('puerta', $puerta)->value('ruta');

        return $ruta;
    }

 private function construirRuta($cod_menu, &$rutaCompleta)
    {
        $menu = DB::table('sys_mainmenu')
            //->select('nombre', 'cod_padre')
            ->where('cod_menu', $cod_menu)
            ->where('cod_rol',session()->get('CodRol'))
            ->first();

        if ($menu && $menu->cod_padre !=='#') {
            //*** Aqui meto los datos al array, html aqui */
            $ruta=$this->getRutaByPuerta($menu->puerta);
            Log::channel('sistema')->info('ruta de la puerta:'. $menu->puerta. ': '.$ruta );
            if($ruta !=='#'){
                $rutaItem=url(route($ruta));
                $rutaCompleta[] = '<a link href="' . $rutaItem . '" >'.$menu->nombre.'</a>';  
            }else{
                $rutaCompleta[] = '<a link href="#" >'.$menu->nombre.'</a>';
            }
           
            //-----------------------------------------------
            if ($menu->cod_padre) {
                $this->construirRuta($menu->cod_padre, $rutaCompleta);
                Log::channel('sistema')->info('se agrega:'. $menu->nombre. 'segun: '.$menu->cod_menu );
            }
     }
        if($menu && $menu->cod_padre =='#'){
            $rutaInicio = url(route('home', ['nav' => true]));
 //$rutaCompleta[] = '<a link href="' . $rutaInicio . '" >'.$menu->nombre.'</a>';  
 $rutaCompleta[] = '<a link href="' . $rutaInicio . '" >Inicio</a>';  
        }
    }



}
