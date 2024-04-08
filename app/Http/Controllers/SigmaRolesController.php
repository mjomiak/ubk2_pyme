<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UyPController;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Rules\datounico;
use PhpParser\Node\Stmt\TryCatch;

class SigmaRolesController extends Controller
{
   
 
    public function readRoles(?bool $exito = null,  ?string $mensaje = null)
    {
        Log::channel('act')->info(auth()->user()->email . 'Solicita listado de roles.');
        $uyp = new UyPController();
        $R = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'sys/rol/read');
        if ($R == false) {
            Log::channel('act')->info(auth()->user()->email . 'No tiene permiso para ver el listado de roles.');
            return view('error')->with('error', 'No tiene permiso para ver el listado de Roles.');
        }
        // rutas del resto de puertas habilitadas
        $rutas = [
            "c" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'sys/rol/create'),
            "u" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'sys/rol/update'),
            "d" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'sys/rol/delete'),
            "l" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'sys/rol/lock'),
        ];
        unset($uyp);
        try {
            $roles = DB::table('sys_roles')->where('borrado', 0)->orderByDesc('activo')->get();
        } catch (Exception $e) {
            Log::channel('sistema')->error(__METHOD__ . ' Ln: ' . __LINE__ . ' Error obteniendo roles  ' . $e);
        }
        //return view('sigma.roles.SigmaListadoRoles', compact('roles', 'rutas'));
        if ($exito === null) {
            Log::channel('act')->info(auth()->user()->email . ' Visualiza listado de roles sin datos ');
            return view('sigma.roles.SigmaListadoRoles', compact('roles', 'rutas'));
        } elseif ($exito === true) {
            Log::channel('act')->info(auth()->user()->email . ' Visualiza listado de roles');
            return view('sigma.roles.SigmaListadoRoles', compact('roles', 'rutas'))->with('success', $mensaje);
        } elseif ($exito === false) {
            Log::channel('act')->info(auth()->user()->email . '  No Visualiza listado de roles, ERROR');
            return view('sigma.roles.SigmaListadoRoles', compact('roles', 'rutas'))->with('error', $mensaje);
        }
    }
   /*
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████   
*/
public function createRoles()
{

   
    $solicitante = auth()->user()->name . '(' . auth()->user()->email . ')';
   
    //permiso para bloquear/desbloquear------------------------------
    $uyp = new UyPController();
    $permiso = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'sys/rol/create');
    unset($uyp);
    if ($permiso == false) {
        Log::channel('act')->warning('En usuario ' . auth()->user()->email . ' intento crear un rol sin permiso.');
        return view('error')->with('error', 'No tiene permiso crear roles');
    }
    unset($uyp);
    $roles= DB::table('sys_roles')->where('borrado', 0)->get();


    return view('sigma.roles.SgmNuevoRol',compact('roles'));
}

    /*
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████   
*/

    public function updateRoles(Request $rq)
    {
        return 'modificar rol';
    }
    /*
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████   
*/
    public function deleteRoles(Request $rq)
    {
        return 'borrar rol';
    }

    /*
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████   
*/

    public function lockRol(Request $rq)
    {

        $solicitado = $this->solicitado($rq->hidden_id);
        $solicitante = auth()->user()->name . '(' . auth()->user()->email . ')';
        $id_solicitante = auth()->user()->id;
        //permiso para bloquear/desbloquear------------------------------
        $uyp = new UyPController();
        $permiso = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'sys/rol/lock');
        unset($uyp);
        if ($permiso == false) {
            Log::channel('act')->warning('En usuario ' . auth()->user()->email . ' intento habilitar/deshabilitar rol id: ' . $solicitado->cod_rol . ' sin permiso.');
            return view('error')->with('error', 'No tiene permiso para bloquer o desbloquear roles');
        }
//dd($solicitado, $rq->id);
        // Validar las contrtaseñas-------------------------------------------------------------------------------------------
        $verbo = '';
        $user = DB::table('users')->where('id', $id_solicitante)->first();
        if ($rq->pass1 == $rq->pass2 && $this->validarEjecucion($rq->pass1) == true) {
Log::channel('act')->info(auth()->user()->email.' ______________________________________ejecuto ');
            if ($this->getRolActivoById($rq->hidden_id) == 1) {
                $this->setRolActivoById($rq->hidden_id, 0);
                $verbo = 'Bloqueado';
            } else {
                $this->setRolActivoById($rq->hidden_id, 1);
                $verbo = 'Desbloqueado';
            }
           // dd($solicitado);
            Log::channel('act')->info(auth()->user()->email . ' ha ' . $verbo . ' el rol ' . $solicitado->nombre);
            return $this->readRoles(true, 'Se ha ' . $verbo . ' el rol ' . $solicitado->nombre);
        } else {
            Log::channel('act')->info(auth()->user()->email . ' ha intentado ' . $verbo . ' el rol ' . $solicitado->nombre . ' proporcionando credenciales incorrectas');
            return $this->readRoles(false, 'Validación de usuario incorrecta, no se permite ejecutar la acción requerida');
        }
    }

    private function solicitado($id)
    {
        try {
            return DB::table('sys_roles')->where('id', $id)->first();
        } catch (Exception $e) {
            Log::channel('sistema')->error(__METHOD__ . ' Ln: ' . __LINE__ . ' error obteniendo cod_rol ' . $e);
        }
    }

    public function saveRol(Request $rq){
    

        $rules=[
            'rol' => ['required', 'integer', 'min:1'],
            'nombre' => ['required', 'string', 'min:3', 'max:255', new datounico($rq->nombre, 'sys_roles', 'nombre', 'El nombre de rol indicado ya está en registrado')],
            'cod' => ['required', 'string', 'min:3', 'max:30',new datounico($rq->cod, 'sys_roles', 'cod_rol', 'El código de rol indicado ya está en registrado')], 
            'descrip' => ['nullable','string', 'min:3', 'max:255'],
        ];

        $messages = [
            'rol.required' => 'El campo rol es obligatorio.',
            'rol.integer' => 'El campo rol debe ser un número entero.',
            'rol.min' => 'Debe seleccionar un rol base.',
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El campo nombre debe ser una cadena de texto.',
            'nombre.min' => 'El campo nombre debe tener al menos :min caracteres.',
            'nombre.max' => 'El campo nombre no debe tener más de :max caracteres.',
            //'nombre.unique' => 'El valor ingresado para nombre ya existe.',
            'cod.required' => 'El campo código es obligatorio.',
            'cod.string' => 'El campo código debe ser una cadena de texto.',
            'cod.min' => 'El campo código debe tener al menos :min caracteres.',
            'cod.max' => 'El campo código no debe tener más de :max caracteres.',
            'descrip.string' => 'El campo descripción debe ser una cadena de texto.',
            'descrip.min' => 'El campo descripción debe tener al menos :min caracteres.',
            'descrip.max' => 'El campo descripción no debe tener más de :max caracteres.',
        ];
        $rq->validate($rules, $messages);

        //insertar nuevo rol-----------------------------------------
        try {
            $idNuevoRol=DB::table('sys_roles')->insertGetId([
                'nombre' => $rq->nombre,
                'cod_rol' => $rq->cod,
                'descrip' => $rq->descrip,
            ]); 
            $this->insertarPermisosRol($rq->rol, $rq->cod);
            $this->insertarMenuRol($rq->rol, $rq->cod);
        } catch (Exception $e) {
        Log::channel('sistema')->error(__METHOD__.' Ln: '. __LINE__.' Error insertando nuevo rol '.$e);
        } 





        return 'rol guardado';
    }

    //getter & setter

    public function getRolActivoById($id)
    {
        try {
            return DB::table('sys_roles')->where('id', $id)->value('activo');
            
        } catch (Exception $e) {
            Log::channel('sistema')->error(__METHOD__ . ' Ln: ' . __LINE__ . ' error obteniendo campo activo de tabla roles ' . $e);
            return null;
        }
    }

    public function setRolActivoById($id, $valor)
    {
        try {
            DB::table('sys_roles')->where('id', $id)->update(['activo' => $valor]);
            return;
        } catch (Exception $e) {
            Log::channel('sistema')->error(__METHOD__ . ' Ln: ' . __LINE__ . ' error actualizando campo activo de tabla roles ' . $e);
            return null;
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

    public function insertarPermisosRol($rolBase, $cod_rol){
        $cod_rol_base=DB::table('sys_roles')->where('id',$rolBase)->value('cod_rol');
        $permisosBase=DB::table('sys_def_permisos')->where('cod_rol',$cod_rol_base)->get();
        foreach($permisosBase as $pb){
            try {
                DB::table('sys_def_permisos')->insert([
                    'puerta' => $pb->puerta ,
                    'permitido' => $pb->permitido,
                    'cod_rol' => $cod_rol,
                    'nombre' => $pb->nombre,
                ]);
            } catch (Exception $e) {
            Log::channel('sistema')->error(__METHOD__.' Ln: '. __LINE__.' error insertando permisos '.$e);
            } 
        }

return;
    }


    public function insertarMenuRol($rolBase, $cod_rol){
        $cod_rol_base=DB::table('sys_roles')->where('id',$rolBase)->value('cod_rol');
        $menuBase=DB::table('sys_mainmenu')->where('cod_rol',$cod_rol_base)->get();

        foreach($menuBase as $mb){
            try {
                DB::table('sys_mainmenu')->insert([
                    'cod_menu' => $mb->cod_menu ,
                    'cod_padre' => $mb->cod_padre,
                    'cod_rol' => $cod_rol,
                    'nombre' => $mb->nombre,
                    'puerta' => $mb->puerta,
                ]);
            } catch (Exception $e) {
            Log::channel('sistema')->error(__METHOD__.' Ln: '. __LINE__.' error insertando menu '.$e);
            } 
        }





return;
    }



    public function getCategorias($cod_rol)
    {
        
        //$cod_rol="'".$cod_rol."'";
        try {
            //->groupBy('categoria')
            $categorias = DB::table('sys_def_permisos')->where('cod_rol',$cod_rol)->groupBy('categoria')->get();
            Log::channel('sistema')->info($categorias);
        } catch (\Exception $e) {
             Log::channel('sistema')->info(__METHOD__.' ln: '.__LINE__.'error consulta categorias'.$e);
        }
       
        Log::channel('sistema')->info($categorias);
        return response()->json($categorias);
    }
}
