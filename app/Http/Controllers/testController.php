<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UyPController;
use Exception;
use Illuminate\Support\Facades\Log;

class testController extends Controller
{
    public function frm_marcaje(Request $r){
        $trabas = DB::table('ubk2_trabajadores')->where('activo',1)->get();
      
        return view('ubk2.testing.marcaje', compact('trabas'));
    }



    public function save_marcaje(Request $request){

        $rules = [
            'trabajador' => 'required|numeric',
            'type_reg' => 'required|numeric',
            'date_reg' =>  'required|date',
            'hour_reg' =>  'required|date_format:H:i',
            'long' => 'required|numeric',
            'lat' => 'required|numeric',
            'cellid' => 'required|numeric',
        ];

        // Mensajes de error personalizados
       

        // Validación
        $validatedData = $request->validate($rules);



        try {
            DB::table('ubk2_registro_horario')->insert([
            'id_trabajador' =>  $request->trabajador,
            'date_reg' =>  $request->date_reg,
            'time_reg' =>  $request->hour_reg,
            'latitud' =>  $request->lat,
            'longitud' =>  $request->long,
            'cellid' =>  $request->cellid,
            'type_reg' =>  $request->type_reg,
             ]);
            
        } catch (Exception $e) {
          Log::info('error: '.$e);
        }





return('save marcacion');

    }
}
