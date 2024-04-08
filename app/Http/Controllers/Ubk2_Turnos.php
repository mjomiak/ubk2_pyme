<?php

namespace App\Http\Controllers;

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


use Illuminate\Http\Request;

class Ubk2_Turnos extends Controller
{
    public function ListadoTurnos(?bool $exito = null,  ?string $mensaje = null)
    {
        Log::channel('act')->info(auth()->user()->email . 'Solicita listado de areas.');
        $uyp = new UyPController();
        $R = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/trn/read');

        if ($R == false) {
            Log::channel('act')->info(auth()->user()->email . 'No tiene permiso para ver el listado de Usuarios.');
            return view('error')->with('error', 'No tiene permiso para ver el listado de Usuarios.');
            // return ('No tiene permisos para listar usuarios');
        }

        $rutas = [
            "c" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'ubk2/trn/create'),
            "u" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'ubk2/trn/update'),
            "d" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'ubk2/trn/delete'),
            "l" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'ubk2/trn/lock'),

        ];

        unset($uyp);

        $idCliente = session('idCliente');
        try {
            $turnos = DB::table('ubk2_turnos')->where('id_cliente', $idCliente)->where('borrado', 0)->orderBy('id_area')->orderByDesc('activo')->get();
        } catch (Exception $e) {
            Log::channel('sistema')->info('error obteniendo turnos: ' . $e);
        }

        foreach ($turnos as $t) {
            $t->id_area = DB::table('ubk2_areas')->where('id_area', $t->id_area)->value('nombre');
        }


        if ($exito === null) {
            Log::channel('act')->info(auth()->user()->email . ' Visualiza listado de turnos sin datos ');
            return view('ubk2.turnos.listado', compact('turnos', 'rutas'));
        } elseif ($exito === true) {
            Log::channel('act')->info(auth()->user()->email . ' Visualiza listado de turnos');
            return view('ubk2.turnos.listado', compact('turnos', 'rutas'))->with('success', $mensaje);
        } elseif ($exito === false) {
            Log::channel('act')->info(auth()->user()->email . '  No Visualiza listado de turnos, ERROR');
            return view('ubk2.turnos.listado', compact('turnos', 'rutas'))->with('error', $mensaje);
        }
    }




    public function FrmNuevoTurno()
    {
        $uyp = new UyPController();
        $c = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/trn/create');
        unset($uyp);
        if ($c == false) {
            return $this->ListadoTurnos(false, "No tiene permiso para crear turnos");
        }
        $areas = DB::table('ubk2_areas')->where('activo', 1)->where('borrado', 0)->where('id_cliente', session('idCliente'))->get();
        return view('ubk2.turnos.nuevo', compact('areas'));
    }

    public function FrmEditarTurno(Request $r)
    {
        $uyp = new UyPController();
        $c = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/trn/update');
        unset($uyp);
        if ($c == false) {
            return $this->ListadoTurnos(false, "No tiene permiso para editar turnos");
        }
        $turno = DB::table('ubk2_turnos')->where('id_turno', $r->id)->first();
        $areas = DB::table('ubk2_areas')->where('activo', 1)->where('borrado', 0)->where('id_cliente', session('idCliente'))->get();
        foreach ($areas as $a) {
            if ($turno->id_area == $a->id_area) {
                $a->selected = 1;
            } else {
                $a->selected = 0;
            }
        }
        //cortar el formato de inicio y fin para no tener prtoblemas en la validacion del formulario
        $turno->inicio =substr($turno->inicio, 0, 5);
        $turno->termino =substr($turno->termino, 0, 5);
        $areas = $areas->sortByDesc('selected');
        //dd($areas);
        return view('ubk2.turnos.editar', compact('turno', 'areas'));
    }

    public function GuardarEdicion(Request $r)
    {

        $uyp = new UyPController();
        $c = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/trn/update');
        unset($uyp);
        if ($c == false) {
            return $this->ListadoTurnos(false, "No tiene permiso para editar turnos");
        }
        // 2.- validacion del 
        if(isset($r->inicio)){$r->inicio=substr($r->inicio, 0, 5);$r->inicio=trim($r->inicio);}
        if(isset($r->termino)){$r->termino=substr($r->termino, 0, 5);$r->termino=trim($r->termino);}
        //dd($r->inicio, $r->termino);
        $rules = [
            'cod_turno' =>  'required|string',
            'nombre' => 'required|string',
            'inicio' => 'required|date_format:H:i',
            'umbral_inicio' => 'numeric|min:0|max:60|required',
            'termino' => 'required|date_format:H:i',
            'umbral_termino' => 'numeric|min:0|max:60|required',
        ];

        $validatedData = $r->validate($rules);

        // 3.- insert con try y log
        $exito = 0;
        try {
            DB::table('ubk2_turnos')->where('id_turno', $r->id_turno)->update([
                'cod_turno' =>  strtoupper($r->cod_turno),
                'id_area'   =>  $r->id_area,
                'nombre'    =>  strtoupper($r->nombre),
                'inicio'    =>  $r->inicio,
                'umbral_inicio' => $r->umbral_inicio,
                'termino'       => $r->termino,
                'umbral_termino' => $r->umbral_termino
            ]);
            $exito = 1;
            Log::channel('act')->info('El usuario ' . auth()->user()->email . ' ha actualizado el turno ' . $r->id_turno);
        } catch (Exception $e) {
            $exito = 0;
            Log::channel('sistema')->error('El usuario ' . auth()->user()->email . 'ha intentado actualizar un turno, error: ' . $e);
        }

        // 4.- devuelvo a listado con mensaje de exito papá!! - o no -
        if ($exito) {
            return $this->ListadoTurnos(true, "Turno actualizado Exitosamente.");
        } else {
            return $this->ListadoTurnos(false, "No se ha podido actualizar el turno.");
        }
    }


    public function BorrarTurno(Request $r)
        {
            $uyp = new UyPController();
            $c = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/trn/delete');
            unset($uyp);
            if ($c == false) {
                return $this->ListadoTurnos(false, "No tiene permiso para borrar turnos.");
            }
    
            // 3.- insert con try y log
            $exito = 0;
            if ($r->pass1 == $r->pass2 && $this->validarEjecucion($r->pass1) == true) {
                try {
                    DB::table('ubk2_turnos')->where('id_turno', $r->hidden_id)->update([
    
                        'borrado' => 1,
                    ]);
    
                    $exito = 1;
                    Log::channel('act')->info('El usuario ' . auth()->user()->email . ' ha borrado el turno ' . $r->nombre_area);
                } catch (Exception $e) {
                    $exito = 0;
                    Log::channel('sistema')->error('El usuario ' . auth()->user()->email . 'ha intentado borrar un turno, error: ' . $e);
                }
            }else{
                return $this->ListadoTurnos(false, "Credenciales incorrectas."); 
            }
            // 4.- devuelvo a listado con mensaje de exito papá!! - o no -
            if ($exito) {
                return $this->ListadoTurnos(true, "Se ha Eliminado el turno exitosamente.");
            } else {
                return $this->ListadoTurnos(false, "No se ha podido eliminar el turno.");
            }
    }
    public function GuardarTurno(Request $r)
    {
        $uyp = new UyPController();
        $c = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'ubk2/trn/create');
        unset($uyp);
        if ($c == false) {
            return $this->listadoTurnos(false, "No tiene permiso para crear turnos");
        }
        // 2.- validacion del request
        if(isset($r->inicio)){$r->inicio=substr($r->inicio, 0, 5);$r->inicio=trim($r->inicio);}
        if(isset($r->termino)){$r->termino=substr($r->termino, 0, 5);$r->termino=trim($r->termino);}
        //dd($r->inicio, $r->termino);
        $rules = [
          
            'cod_area' =>  'numeric|min:1',
            'cod_turno' =>  'required|string',
            'nombre' => 'required|string',
            'inicio' => 'required|date_format:H:i',
            'umbral_inicio' => 'numeric|min:0|max:60|required',
            'termino' => 'required|date_format:H:i',
            'umbral_termino' => 'numeric|min:0|max:60|required',
        ];

        $validatedData = $r->validate($rules);

        // 3.- insert con try y log
        $exito = 0;
        try {
            DB::table('ubk2_turnos')->insert([
                'id_cliente'=> session('idCliente'),
                'cod_turno' =>  strtoupper($r->cod_turno),
                'id_area'   =>  $r->id_area,
                'nombre'    =>  strtoupper($r->nombre),
                'inicio'    =>  $r->inicio,
                'umbral_inicio' => $r->umbral_inicio,
                'termino'       => $r->termino,
                'umbral_termino' => $r->umbral_termino,
                'activo' =>1,
                'accion' => 0,
                'borrado'=>0
            ]);
            $exito = 1;
            Log::channel('act')->info('El usuario ' . auth()->user()->email . ' ha creado el turno ' . $r->id_turno);
        } catch (Exception $e) {
            $exito = 0;
            Log::channel('sistema')->error('El usuario ' . auth()->user()->email . 'ha intentado crear un turno, error: ' . $e);
        }

        // 4.- devuelvo a listado con mensaje de exito papá!! - o no -
        if ($exito) {
            return $this->ListadoTurnos(true, "Turno creado Exitosamente.");
        } else {
            return $this->ListadoTurnos(false, "No se ha podido crear el turno.");
        } 
    }

    public function BloquearTurno()
    {
        return ' bloquar turno';
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
