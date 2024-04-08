<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Rules\rut;
use App\Rules\rutunique;
use App\Rules\rutuniqueupt;
use App\Rules\datounico;
use App\Rules\datounicoupt;
use Illuminate\Support\Collection;
use stdClass;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PlantCargaMasivaTrab;


use App\Http\Controllers\UyPController;
use App\Imports\CargaMasivaTrab;

class Ubk2_Trabajadores extends Controller
{
    public function index()
    {

        //obtengo todos los permisos y rutas para la funcionalidad

        //dd(session('CodRol'));

        $uyp = new UyPController();
        $C = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/trab/create'); //permiso para crear recurso
        $R = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/trab/read'); //permiso para listar recurso
        $U = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/trab/update'); //permiso para actualizar recurso
        $D = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/trab/delete'); //permiso para borrar(marcar) recurso
        $L = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/trab/lock'); // permiso para bloquear recurso
        $DH = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/trab/inhibit'); //permiuso para deshabilitar recurso, si existe
        $RD = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/trab/realdel'); // permiso para liminacion rreal
        $DT = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/trab/detail'); //permiso para ver detalles (auditoria)
        $LD = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/trab/load'); //permiso para ver detalles (auditoria)

        if ($R == false) {
            return ('No tiene permisos para listar Trabajadores');
        }

        $rutas = [
            "c" => $uyp->getRutaFunc('ubk2/trab/create'),
            "u" => $uyp->getRutaFunc('ubk2/trab/update'),
            "d" => $uyp->getRutaFunc('ubk2/trab/delete'),
            "dh" => $uyp->getRutaFunc('ubk2/trab/inhibit'),
            "rd" => $uyp->getRutaFunc('ubk2/trab/realdel'),
            "dt" => $uyp->getRutaFunc('ubk2/trab/detail'),
            "l" => $uyp->getRutaFunc('ubk2/trab/lock'),
            "ld" => $uyp->getRutaFunc('ubk2/trab/load'),
        ];

        //dd($rutas);

        //muestra listado de trabajadores
        //return('listado');
        $trabajadores = DB::table('ubk2_trabajadores')->where('id_cliente',session('idCliente'))->where('activo', 1)->get();
        foreach($trabajadores as $t){
            $t->area= $this->getNombreAreaById($t->id_area);
        }
        return view('ubk2.trabajadores.listado', compact('trabajadores', 'rutas'));
    }

    public function getNombreAreaById($id){
        try {
            $area=DB::table('ubk2_areas')->where('id_area',$id)->value('nombre');
        } catch (Exception $e) {
            Log::channel('sistema')->info('fuck ln 66');
        }
        if($area == null){$area = 'Indefinida';}
        return $area;
    }

    public function nuevoTrabajador(Request $r)
    {
        // devuelve formulario de alta de trabajador
        // verifico si tiene el permiso
        $uyp = new UyPController();
        $crear = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/trab/create');
        if (!$crear) {
            return "No tiene permiso para crear trzabajadores";
        } else {
            $areas = DB::table('ubk2_areas')->where('activo', 1)->where('id_cliente', session('idCliente'))->get();
            return view('ubk2.trabajadores.nuevo', compact('areas'));
        }
    }


    public function guardarTrabajador(Request $request)
    {
        // autoriza con la puerta de nuevo, la misma del formulario, son dos metodos del mismo permiso
        //dd($request);

        $rules = [
            'nombreCompleto' => 'required|string|max:255',
            'rut' => ['required', new rut, 'regex:/^[0-9.\-Kk]+$/i', new rutunique($request->rut, 'ubk2_trabajadores', 'rut')],
            'movil' =>  ['required', new datounico($request->movil, 'ubk2_trabajadores', 'movil', 'En nro. de movil indicado ya está registrado')],
            'correo' =>  ['required', 'email', new datounico($request->correo, 'ubk2_trabajadores', 'correo', 'El correo indicado ya está en registrado')],
            'area' => 'required|numeric|min:0',
        ];

        // Mensajes de error personalizados
        $messages = [
            'nombreCompleto.required' => 'El campo nombre completo es obligatorio.',
            'nombreCompleto.max' => 'El campo nombre completo no debe superar los :max caracteres.',
            'rut.required' => 'El campo RUT es obligatorio.',
            'rut.regex' => 'El campo RUT debe contener solo números del 0 al 9 o la letra \'k\' (mayúscula o minúscula).',
            'rut.rutunique' => 'El RUT ya está en uso (personalizado)',
            'movil.required' => 'El campo móvil es obligatorio.',
            'movil.numeric' => 'El campo móvil debe ser un valor numérico.',
            'area.required' => 'El campo área es obligatorio.',
            'area.numeric' => 'El campo área debe ser un valor numérico.',
            'area.min' => 'Debe seleccionar un área.',
            'correo.required' => 'El campo de correo electrónico es obligatorio.',
            'correo.email' => 'El formato del correo electrónico no es válido.',
            'correo.datounico' => 'El formato del correo electrónico ya esta en uso.',

        ];

        // Validación
        $validatedData = $request->validate($rules, $messages);


        //una vez validado voy a separar rut y dv
        // 1- Quitar espacios
        $request['rut'] = trim($request['rut']);
        // 2-Quitar puntos
        $request['rut'] = str_replace(".", "", $request['rut']);
        // 3- Separar rut e dv
        $rutCompleto = explode("-", $request['rut']);
        $request['rut'] = $rutCompleto[0];
        $request['dv'] = strtoupper($rutCompleto[1]);




        try {
            // Obtén los datos del formulario
            $nombreCompleto = strtoupper($request->input('nombreCompleto'));
            $movil = $request->input('movil');
            $area = $request->input('area');

            // Inserta los datos en la tabla
            DB::table('ubk2_trabajadores')->insert([
                'nombrecompleto' => $nombreCompleto,
                'rut' =>  $request['rut'],
                'dv' =>  $request['dv'],
                'correo' =>  $request['correo'],
                'movil' => $movil,
                'id_area' => $area,
                'activo' => 1,
                'borrado' => 0,
                'accion' => 0,

                // Agrega más campos según la estructura de tu tabla
            ]);

            // Redirige con un mensaje de éxito
            return redirect()->back()->with('success', 'Formulario enviado correctamente');
        } catch (\Exception $e) {
            // Manejo de errores
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Error al procesar el formulario: ' . $e->getMessage());
        }




        //--------



    }

    public function modificarTrabajador(Request $r)
    {
        // tiene una puerta con permiso ubk2/modif/trab pero no esta asociado a ningun menu
        // es para el boton en el listado.
        $id = $r->id;

        $trabajador = DB::table('ubk2_trabajadores')->where('id_trab', $id)->where('activo', 1)->where('borrado', 0)->first();
        //$area = DB::table('ubk2_areas')->where('id_area', $trabajador->id_area)->first();
        $areas=DB::table('ubk2_areas')->where('id_cliente', session('idCliente'))->where('activo',1)->get();

        foreach($areas as $a){
            if($a->id_area==$trabajador->id_area){
                $a->selected=1;
            }else{$a->selected=0;};
        }
        $areas=$areas->sortByDesc('selected');
     //   dd($areas);

        

     //   $areas = DB::table('ubk2_areas')->where('activo', 1)->get();

        // dd($id, $area);
        return view('ubk2.trabajadores.edit', compact('trabajador', 'areas'));
    }

    public function bajaTrabajador(Request $r)
    {
        return ('Baja del trabajador');
    }



    public function realDeleteTrabajador(Request $request)
    {

        DB::table('ubk2_trabajadores')->where('id', $request->id)->delete();
        return $this->index();
    }


    public function updateTrabajador(Request $request)
    {
        // regla para ver que sea unico menos 1
        //return "update trabajador";

        //dd($request->request);



        $rules = [
            'nombreCompleto' => 'required|string|max:255',
            'rut' => ['required', new rut, 'regex:/^[0-9.\-Kk]+$/i', new rutuniqueupt($request->rut, 'ubk2_trabajadores', 'rut')],
            'movil' =>  ['required', new datounicoupt($request->movil, 'ubk2_trabajadores', 'movil', 'En nro. de movil indicado ya está registrado')],
            'correo' =>  ['required', 'email', new datounicoupt($request->correo, 'ubk2_trabajadores', 'correo', 'El correo indicado ya está en registrado')],
            'area' => 'required|numeric|min:0',
        ];

        // Mensajes de error personalizados
        $messages = [
            'nombreCompleto.required' => 'El campo nombre completo es obligatorio.',
            'nombreCompleto.max' => 'El campo nombre completo no debe superar los :max caracteres.',
            'rut.required' => 'El campo RUT es obligatorio.',
            'rut.regex' => 'El campo RUT debe contener solo números del 0 al 9 o la letra \'k\' (mayúscula o minúscula).',
            'rut.rutuniqueupt' => 'El RUT ya está en uso (personalizado)',
            'movil.required' => 'El campo móvil es obligatorio.',
            'movil.numeric' => 'El campo móvil debe ser un valor numérico.',
            'area.required' => 'El campo área es obligatorio.',
            'area.numeric' => 'El campo área debe ser un valor numérico.',
            'area.min' => 'Debe seleccionar un área.',
            'correo.required' => 'El campo de correo electrónico es obligatorio.',
            'correo.email' => 'El formato del correo electrónico no es válido.',
            'correo.datounicoupt' => 'El formato del correo electrónico ya esta en uso.',

        ];

        // Validación
        $validatedData = $request->validate($rules, $messages);
        Log::info('pase la validacion de update');

        //una vez validado voy a separar rut y dv
        // 1- Quitar espacios
        $request['rut'] = trim($request['rut']);
        // 2-Quitar puntos
        $request['rut'] = str_replace(".", "", $request['rut']);
        // 3- Separar rut e dv
        $rutCompleto = explode("-", $request['rut']);
        $request['rut'] = $rutCompleto[0];
        $request['dv'] = strtoupper($rutCompleto[1]);




        try {
            // Obtén los datos del formulario
            $nombreCompleto = strtoupper($request->input('nombreCompleto'));
            $movil = $request->input('movil');
            $area = $request->input('area');

            // Inserta los datos en la tabla
            Log::info('Actualizo id: ' . $request->id);

            DB::table('ubk2_trabajadores')->where('id_trab', $request->id)->update([
                'nombrecompleto' => $nombreCompleto,
                'rut' =>  $request['rut'],
                'dv' =>  $request['dv'],
                'correo' => $request['correo'],
                'movil' => $movil,
                'id_area' => $area,
                'activo' => 1,
                'borrado' => 0,
                'accion' => 0,

                // Agrega más campos según la estructura de tu tabla
            ]);

            // Redirige con un mensaje de éxito
            return redirect()->route('Ubk2ListadoTrab')->with('success', '<b>ÉXITO</b>: Los datos del trabajador actualizados.');
        } catch (\Exception $e) {
            // Manejo de errores
            dd($e->getMessage());
            return redirect()->back()->with('error', '<b>ERROR</b>: No fue posible actualizar los datos, detalle: ' . $e->getMessage());
        }
    }



    public function CargaMasivaTrabajador (){
        return view('ubk2.trabajadores.cargamasiva');
    }


public function ExportCMtrab(

){
    $id_cliente = session('idCliente'); 
    Log::info('id cliente en el controller para plantilla:'. $id_cliente);
    return Excel::download(new PlantCargaMasivaTrab($id_cliente), 'plantilla.xlsx');
}

public function ImportCMtrab(Request $request)
    {
        $id_cliente = session('idCliente'); 
       /* Log::info('id cliente en el controller para subir:'. $id_cliente);
        Excel::import(new CargaMasivaTrab($id_cliente), $request->file('file'));
        */

      //-----
      try {
        $import = new CargaMasivaTrab($id_cliente);
        Excel::import($import, $request->file('file'));

        // Obtener el resultado de la importación
        $resultado = $import->getResultado();
        unset($import);
       return view('ubk2.trabajadores.resumenCM', compact('resultado'));
    } catch (\Exception $e) {
        Log::info('meter algo aqui '.$e);
    }




      //---
    }



}
