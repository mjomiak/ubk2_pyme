<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class Ubk2_GestTrab extends Controller
{
    public function HorasExtra(Request $r)
    {
        return ('Ingreso de horas extra');
    }

    public function Vacaciones(Request $r)
    {
        return ('Ingreso de horas Vacaciones');
    }

    public function Licencias(Request $r)
    {
        return ('Ingreso de Licencias');
    }


    public function GuardaHorasExtra(Request $r)
    {
        return (' Guarda Ingreso de horas extra');
    }

    public function GuardaVacaciones(Request $r)
    {
        return (' Guarda Ingreso de horas Vacaciones');
    }

    public function GuardaLicencias(Request $r)
    {
        return ('Guarda Ingreso de Licencias');
    }


    public function FormConsultaMarcaje(Request $r)
    {

    }

    public function marcajes(Request $r)
    {
        $id = $r->id;
        $id = 1;

        try {
            $trab=DB::table('ubk2_trabajadores')->where('id',$id)->first();
        } catch (Exception $e) {
            Log::error('Ubk2_GestTrab::marcajes -> Error al obtener datos del trabajador con id: '.$id.':'.$e);
        }




        try {
            $marcaciones=DB::table('ubk2_registro_horario')->where('id_trabajador',$id)->orderBy('date_reg')->orderBy('time_reg')->get();
        } catch (Exception $e) {
            Log::error('Ubk2_GestTrab::marcajes -> Error al obtener marcaciones:'.$e);
        }

        //transformaciones old school 

    foreach ($marcaciones as $m){
        if ($m->type_reg == 1){$m->type_reg='ENTRADA';}
        if ($m->type_reg == 2){$m->type_reg='SALIDA';}
    }


      
return view('ubk2.gestion.marcajes', compact('marcaciones'))->with('trab',$trab);

    }
}
