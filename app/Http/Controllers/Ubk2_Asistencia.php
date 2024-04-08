<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use Illuminate\Http\Request;

class Ubk2_Asistencia extends Controller
{
    public function frmConsultaAsistencia(Request $r){
        $trabas = DB::table('ubk2_trabajadores')->where('activo', 1)->get();
        return view('ubk2.asistencia.consulta',compact('trabas'));
    }


public function resultado(Request $r){
$desde=$r->desde.' 00:00:00';
$hasta=$r->hasta.' 23:59:59';

$fechas = [];

    $fechaActual = Carbon::parse($desde);

    while ($fechaActual->lte(Carbon::parse($hasta))) {
        $fechas[] = $fechaActual->toDateString(); // Agrega la fecha actual al array en el formato deseado
        $fechaActual->addDay(); // Avanza un día
    }

dd($fechas);


$id =$r->trabajador;
    $resultado=DB::table('ubk2_registro_horario')
    ->where('id_trabajador', $id)
    ->whereBetween('date_reg', [$desde, $hasta])
    ->orderBy('date_reg')
    ->get();

    dd($resultado);

}



}
