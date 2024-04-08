<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UyPController;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Rules\rut;
use App\Rules\rutunique;
use App\Rules\rutuniqueupt;
use App\Rules\datounico;
use App\Rules\datounicoupt;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Mail;
use App\Mail\NuevoUsuario;
use App\Mail\ResetContra;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Database\Query\Builder;
use App\Http\Controllers\LogController;

class Ubk2_Areas extends Controller
{


    public function listarAreas(?bool $exito = null,  ?string $mensaje = null)
    {

        Log::channel('act')->info(auth()->user()->email . 'Solicita listado de areas.');
        $uyp = new UyPController();
        $R = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/ara/read');

        if ($R == false) {
            Log::channel('act')->info(auth()->user()->email . 'No tiene permiso para ver el listado de Usuarios.');
            return view('error')->with('error', 'No tiene permiso para ver el listado de Usuarios.');
            // return ('No tiene permisos para listar usuarios');
        }

        $rutas = [
            "c" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'ubk2/ara/create'),
            "u" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'ubk2/ara/update'),
            "d" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'ubk2/ara/delete'),
            "l" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'ubk2/ara/lock'),

        ];

        unset($uyp);
        $idCliente = session('idCliente');
        try {
            $areas = DB::table('ubk2_areas')->where('id_cliente', $idCliente)->where('borrado', 0)->orderByDesc('activo')->get();
        } catch (Exception $e) {
            Log::channel('sistema')->info('error obteniendo areas: ' . $e);
        }

        //dd($rutas);

        if ($exito === null) {
            Log::channel('act')->info(auth()->user()->email . ' Visualiza listado de Areas sin datos ');
            return view('ubk2.areas.listado', compact('areas', 'rutas'));
        } elseif ($exito === true) {
            Log::channel('act')->info(auth()->user()->email . ' Visualiza listado de Areas');
            return view('ubk2.areas.listado', compact('areas', 'rutas'))->with('success', $mensaje);
        } elseif ($exito === false) {
            Log::channel('act')->info(auth()->user()->email . '  No Visualiza listado de Areas, ERROR');
            return view('ubk2.areas.listado', compact('areas', 'rutas'))->with('error', $mensaje);
        }
    }
    public function FrmCrearArea()
    {
        return view('ubk2.areas.nueva');
    }
    public function guardarNuevaArea(Request $r)
    {
        //dd($r->request);
        // 1.- validar que tenga permiso 

        $uyp = new UyPController();
        $c = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/ara/create');
        unset($uyp);
        if ($c == false) {
            return view('error')->with('error', 'No tiene permiso para crear usuarios');
        }
        // 2.- validacion del request
        $rules = [
            'nombre_area' =>  'required',
            'descrip_area' => 'nullable|string',
            'encargado_area' => 'required|string',
        ];

        $validatedData = $r->validate($rules);

        // 3.- insert con try y log
        $exito = 0;
        try {
            DB::table('ubk2_areas')->insert([
                'id_cliente' => session('idCliente'),
                'nombre' => strtoupper($r->nombre_area),
                'descrip' => strtoupper($r->descrip_area),
                'encargado' => strtoupper($r->encargado_area),
                'activo' => 1,
                'accion' => 0,
                'borrado' => 0,
            ]);
            $exito = 1;
            Log::channel('act')->info('El usuario ' . auth()->user()->email . ' ha creado el area ' . $r->nombre_area);
        } catch (Exception $e) {
            $exito = 0;
            Log::channel('sistema')->error('El usuario ' . auth()->user()->email . 'ha intentado crear un area, error: ' . $e);
        }

        // 4.- devuelvo a listado con mensaje de exito papá!! - o no -
        if ($exito) {
            return $this->listarAreas(true, "Área creada Exitosamente.");
        } else {
            return $this->listarAreas(false, "No se ha podido crear el área.");
        }
    }
    public function formEditarArea(Request $r)
    {

        $uyp = new UyPController();
        $c = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/ara/update');
        unset($uyp);
        if ($c == false) {
            return view('error')->with('error', 'No tiene permiso para crear usuarios');
        }

        $area = DB::table('ubk2_areas')->where('id_area', $r->id)->first();
        // dd($area);
        return view('ubk2.areas.editar')->with('area', $area);
    }
    public function actualizarArea(Request $r)
    {
        $uyp = new UyPController();
        $c = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/ara/update');
        unset($uyp);
        if ($c == false) {
            return view('error')->with('error', 'No tiene permiso para crear usuarios');
        }
        // 2.- validacion del request
        $rules = [
            'nombre_area' =>  'required',
            'descrip_area' => 'nullable|string',
            'encargado_area' => 'required|string',
        ];

        $validatedData = $r->validate($rules);

        // 3.- insert con try y log
        $exito = 0;
        try {
            DB::table('ubk2_areas')->where('id_area', $r->id_area)->where('id_cliente', $r->id_cliente)->update([

                'nombre' => strtoupper($r->nombre_area),
                'descrip' => strtoupper($r->descrip_area),
                'encargado' => strtoupper($r->encargado_area),
            ]);
            $exito = 1;
            Log::channel('act')->info('El usuario ' . auth()->user()->email . ' ha actualizado el area ' . $r->nombre_area);
        } catch (Exception $e) {
            $exito = 0;
            Log::channel('sistema')->error('El usuario ' . auth()->user()->email . 'ha intentado actualizar un area, error: ' . $e);
        }

        // 4.- devuelvo a listado con mensaje de exito papá!! - o no -
        if ($exito) {
            return $this->listarAreas(true, "Área actualizada Exitosamente.");
        } else {
            return $this->listarAreas(false, "No se ha podido actualizar el área.");
        }
    }
    public function habilitarArea(Request $r)
    {


        $uyp = new UyPController();
        $c = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/ara/lock');
        unset($uyp);
        if ($c == false) {
            return view('error')->with('error', 'No tiene permiso para crear usuarios');
        }

        // 3.- insert con try y log
        $exito = 0;
  //      $estado_actual=DB::table('ubk2_areas')->where('id_area', $r->hidden_id)->value('activo');
        $estado=abs((intval(DB::table('ubk2_areas')->where('id_area', $r->hidden_id)->value('activo')))-1);
//dd($estado_actual, $estado);
        try {
            DB::table('ubk2_areas')->where('id_area', $r->hidden_id)->update([

                'activo' => $estado,
            ]);
            if ($estado == 0) {
                $verbo = 'Desbloqueado';
            } else {
                $verbo = 'Bloqueado';
            }
            $exito = 1;
            Log::channel('act')->info('El usuario ' . auth()->user()->email . ' ha ' . $verbo . ' el area ' . $r->nombre_area);
        } catch (Exception $e) {
            $exito = 0;
            Log::channel('sistema')->error('El usuario ' . auth()->user()->email . 'ha intentado ' . $verbo . ' un area, error: ' . $e);
        }

        // 4.- devuelvo a listado con mensaje de exito papá!! - o no -
        if ($exito) {
            return $this->listarAreas(true, $verbo . " de área exitoso.");
        } else {
            return $this->listarAreas(false, 'No se ha podido ejecutar ' . $verbo . " de área.");
        }
    }
    public function borrarArea(Request $r)
    {
        //dd($r);
      //  return 'params: ' . $r->pass1 . '  ' . $r->pass2 . '  ' . $r->hidden_id;

        $uyp = new UyPController();
        $c = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/ara/delete');
        unset($uyp);
        if ($c == false) {
            return view('error')->with('error', 'No tiene permiso para borrar areas');
        }

        // 3.- insert con try y log
        $exito = 0;
        if ($r->pass1 == $r->pass2 && $this->validarEjecucion($r->pass1) == true) {
            try {
                DB::table('ubk2_areas')->where('id_area', $r->hidden_id)->update([

                    'borrado' => 1,
                ]);

                $exito = 1;
                Log::channel('act')->info('El usuario ' . auth()->user()->email . ' ha borrado el area ' . $r->nombre_area);
            } catch (Exception $e) {
                $exito = 0;
                Log::channel('sistema')->error('El usuario ' . auth()->user()->email . 'ha intentado borrar un area, error: ' . $e);
            }
        }else{
            return $this->listarAreas(false, "Credenciales incorrectas."); 
        }
        // 4.- devuelvo a listado con mensaje de exito papá!! - o no -
        if ($exito) {
            return $this->listarAreas(true, "Se ha Eliminado el área exitosamente.");
        } else {
            return $this->listarAreas(false, "No se ha podido eliminar el área.");
        }
    }


    function validarEjecucion($pass): bool
    {
        $email = auth()->user()->email;
        $encPass =  auth()->user()->password;
        if (password_verify($pass, $encPass)) {
            return true;
        } else {
            return false;
        }
    }
}
