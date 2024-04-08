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

class SigmaUsuariosController extends Controller
{

    /*
███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
 _ _     _            _         _   _                      _                ███████████████████████████████████████████████████████████████████████████████████████
| (_)   | |          | |       | | | |                    (_)               ███████████████████████████████████████████████████████████████████████████████████████
| |_ ___| |_ __ _  __| | ___   | | | |___ _   _  __ _ _ __ _  ___  ___      ███████████████████████████████████████████████████████████████████████████████████████
| | / __| __/ _` |/ _` |/ _ \  | | | / __| | | |/ _` | '__| |/ _ \/ __|     ███████████████████████████████████████████████████████████████████████████████████████
| | \__ \ || (_| | (_| | (_) | | |_| \__ \ |_| | (_| | |  | | (_) \__ \     ███████████████████████████████████████████████████████████████████████████████████████
|_|_|___/\__\__,_|\__,_|\___/   \___/|___/\__,_|\__,_|_|  |_|\___/|___/     ███████████████████████████████████████████████████████████████████████████████████████
███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████                                                                       
                                                        

*/



    public function listarUsuarios(?bool $exito = null,  ?string $mensaje = null)
    {
       
        Log::channel('act')->info(auth()->user()->email . 'Solicita listado de Usuarios.');
        $uyp = new UyPController();
        $R = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'sys/usr/read');

        if ($R == false) {
            Log::channel('act')->info(auth()->user()->email . 'No tiene permiso para ver el listado de Usuarios.');
            return view('error')->with('error', 'No tiene permiso para ver el listado de Usuarios.');
           // return ('No tiene permisos para listar usuarios');
        }

        $rutas = [
            "c" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'sys/usr/create'),
            "u" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'sys/usr/update'),
            "d" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'sys/usr/delete'),
            "dh" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'sys/usr/inhibit'),
            "rd" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'sys/usr/realdel'),
            "dt" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'sys/usr/detail'),
            "l" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'sys/usr/lock'),
            "pr" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'sys/usr/pwdrst'),
            "fpr" => $uyp->GetMeRuta(session('idUser'), session('CodRol'), 'sys/usr/fpwdrst'),
        ];

        unset($uyp);

        try {
            $usuarios = DB::table('users')
                ->where('borrado', 0)->orderByDesc('activo')
                ->get();
        } catch (Exception $e) {
          
            Log::channel('sistema')->error(__METHOD__ . ' Ln: ' . __LINE__ . ' Error al obtener los usuarios: ' . $e);
        }

        if ($exito === null) {
            Log::channel('act')->info(auth()->user()->email . ' Visualiza listado de Usuarios sin datos ');
            return view('sigma/users/SigmaListadoUsuarios', compact('usuarios', 'rutas'));
        } elseif ($exito === true) {
            Log::channel('act')->info(auth()->user()->email . ' Visualiza listado de Usuarios');
            return view('sigma/users/SigmaListadoUsuarios', compact('usuarios', 'rutas'))->with('success', $mensaje);
        } elseif ($exito === false) {
            Log::channel('act')->info(auth()->user()->email . '  No Visualiza listado de Usuarios, ERROR');
            return view('sigma/users/SigmaListadoUsuarios', compact('usuarios', 'rutas'))->with('error', $mensaje);
        }
    }

    /*
███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
|  ___|                   | \ | |               | | | |              ███████████████████████████████████████████████████████████████████████████████████████
| |_ ___  _ __ _ __ ___   |  \| | _____      __ | | | |___  ___ _ __ ███████████████████████████████████████████████████████████████████████████████████████
|  _/ _ \| '__| '_ ` _ \  | . ` |/ _ \ \ /\ / / | | | / __|/ _ \ '__|███████████████████████████████████████████████████████████████████████████████████████
| || (_) | |  | | | | | | | |\  |  __/\ V  V /  | |_| \__ \  __/ |   ███████████████████████████████████████████████████████████████████████████████████████
\_| \___/|_|  |_| |_| |_| \_| \_/\___| \_/\_/    \___/|___/\___|_|   ███████████████████████████████████████████████████████████████████████████████████████
███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████                                                                     
*/

    public function NuevoUsuario(Request $r)
    {
        $uyp = new UyPController();
        $c = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'sys/usr/create');
        unset($uyp);
        if ($c == false) {
            return view('error')->with('error', 'No tiene permiso para crear usuarios');  
          
        }

        try {
            $roles = DB::table('sys_roles')->where('activo', true)->get();
        } catch (Exception $e) {
            Log::channel('sistema')->info(__METHOD__ . ' Ln: ' . __LINE__ . ' Error al obtener los roles: ' . $e);
        }
        Log::channel('act')->info(auth()->user()->email . ' Ingresa al alta de usuario.');
        return view('sigma.users.SgmNuevoUsuario', compact('roles'));
    }

    /*
███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
 _____                   _   _                 _   _               ██████████████████████████████████████████████████████████████████████████████████████████████
/  ___|                 | \ | |               | | | |              ██████████████████████████████████████████████████████████████████████████████████████████████
\ `--.  __ ___   _____  |  \| | _____      __ | | | |___  ___ _ __ ██████████████████████████████████████████████████████████████████████████████████████████████
 `--. \/ _` \ \ / / _ \ | . ` |/ _ \ \ /\ / / | | | / __|/ _ \ '__|██████████████████████████████████████████████████████████████████████████████████████████████
/\__/ / (_| |\ V /  __/ | |\  |  __/\ V  V /  | |_| \__ \  __/ |   ██████████████████████████████████████████████████████████████████████████████████████████████
\____/ \__,_| \_/ \___| \_| \_/\___| \_/\_/    \___/|___/\___|_|   █████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
*/

    public function GuardarNuevoUsuario(Request $r)
    {
        //valido puerta UyP
        $uyp = new UyPController();
        $c = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'sys/usr/create');
        unset($uyp);
        if ($c == false) {
            return view('error')->with('error', 'No tiene permiso para crear usuarios'); 
        }

        $rules = [
            'email' =>  ['required', 'email', new datounico($r->email, 'users', 'email', 'El correo indicado ya está en registrado')],
            'nombre' => 'required|string|max:255',
            'rol' => 'required|array|min:0',
        ];

        $mensajes = [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.max' => 'El campo nombre  no debe superar los :max caracteres.',

            'rol.required' => 'El campo área es obligatorio.',

            'rol.min' => 'Debe seleccionar un área.',
            'email.required' => 'El campo de correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.datounico' => 'El formato del correo electrónico ya esta en uso.',

        ];

        // Validación
        $validatedData = $r->validate($rules, $mensajes);
        $pass = $this->generarPasswordAleatorio();
        $idCliente = session('idCliente');
        try {
            $nuevoId = DB::table('users')->insertGetId([
                'name' => strtoupper($r->nombre),
                'id_cliente' => $idCliente,
                'email' => $r->email,
                'password' => bcrypt($pass),
                'accion' => 1, /* forzar cambio de contraseña*/
            ]);
            Log::channel('act')->info(__METHOD__ . ': ' . auth()->user()->email . ' inserto el usuario (faltan roles):' . $r->nombre . ', correo: ' . $r->email . ' id: ' . $nuevoId . ' pw inicial: ' . $pass);
        } catch (Exception $e) {
            Log::channel('sistema')->error(__METHOD__ . ' Ln: ' . __LINE__ . ' Error al Guardar nuevo usuario:' . $e);
        }
        $creado = $r;

        //insertar en roles
        //inserto todos los roles desactivados al usuario
        $allRoles=DB::table('sys_roles')->get();
        foreach($allRoles as $tr){
            try {
                DB::table('sys_asoc_rol_user')->insert([
                    'id_user' => $nuevoId,
                    'cod_rol' => $tr->cod_rol,
                    'activo' => 0,
                ]);
                Log::channel('act')->info(' se insertto rol '.$tr->cod_rol.' INACTIVO para:' . $creado->email);
                        } catch (Exception $e) {
            Log::channel('sistema')->error(__METHOD__.' Ln: '. __LINE__.' Hubo un error creando los roles para el usuario '.$e);
            } 
        }

//activo los roles solicitados en el formulario
foreach ($r->rol as $rol) {
    try {
        DB::table('sys_asoc_rol_user')->where('id_user',$nuevoId)->where('cod_rol',$this->getCodRol($rol))->update(['activo'=>1]);
        Log::channel('act')->info(' se habilitó el rol '.$tr->cod_rol.' para:' . $creado->email);
    } catch (Exception $e) {
    Log::channel('sistema')->error(__METHOD__.' Ln: '. __LINE__.'  Hubo un error habilitando los roles para el usuario '.$e);
    } 
}
   

        //mandar correo
        /* Logica para correos de prueba*------------------------------------------------------------------------------------------------------------------*/
        $pruebaCorreo = env('MAIL_TEST');
        Log::channel('sistema')->warning(__METHOD__ . ' ln: ' . __LINE__ . ': El modo de email de prueba está establecido en: ' . $pruebaCorreo);

        if ($pruebaCorreo == 'true') {
            $correo = 'mjomiak@gmail.com';
        } else {
            $correo = $r->email;
        }
        Log::channel('sistema')->warning(__METHOD__ . ' ln: ' . __LINE__ . ': se utilizara la direccion: ' . $correo);
        /* -----------------------------------------------------------------------------------------------------------------------------------------------------*/

        try {
            Mail::to($correo)->send(new NuevoUsuario($nuevoId, $pass));
        } catch (Exception $e) {
            Log::channel('sistema')->error(__METHOD__ . ' ln: ' . __LINE__ . ': No se ha enviado el correo de alta al usuario:' . $creado->nombre . ', correo: ' . $creado->email . 'Error: ' . $e);
            return $this->listarUsuarios(false, "No se ha podido enviar el e-mail de alta, el usuario a sido creado, resetear cuenta.");
        }
        Log::channel('act')->info(auth()->user()->email . ' : Se inserto el usuario:' . $creado->nombre . ', correo: ' . $creado->email . ' id: ' . $nuevoId . ' pw inicial: ' . $pass);
        return $this->listarUsuarios(true, "Usuario Creado correctamente");
    }

    /*
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████   
*/

    public function DetalleUsuario(Request $r)
    {
        $usuario = DB::table('users')->where('id', $r->id)->first();
        $rolesUser = DB::table('sys_asoc_rol_user')->where('id_user', $r->id)->where('activo', true)->get();
        $id_roles = [];
        foreach ($rolesUser as $rol) {
            // $_codrol= DB::table('sys_roles')->where('cod_rol', $rol->cod_rol)->value('id');
            $_descrip = DB::table('sys_roles')->where('cod_rol', $rol->cod_rol)->value('descrip');
            $id_roles[] = $rol->cod_rol . ' - ' . $_descrip;
        }

        $stringBuscado = DB::table('users')->where('id', $r->id)->value('email');
        $log = new LogController();

        $actividades = $log->UltimasActividadesUser($stringBuscado, 30);
        unset($log);

        $unix_sesion = DB::table('sessions')->where('user_id', $r->id)->value('last_activity');
        $us = date('Y-m-d H:i:s', $unix_sesion);

        Log::channel('act')->info(auth()->user()->email . ' Visualiza detalles de usuario: ' . $usuario->email);
        return view('sigma.users.SigmaDetalleUsuario')
            ->with('user', $usuario)
            ->with('roles', $id_roles)
            ->with('us', $us)
            ->with('log', $actividades);
    }
    /*modificacion*/

    /*
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████   
*/

    public function EditarUsuario(Request $r)

    {

        $uyp = new UyPController();
        $permiso = $uyp->VerificarPermisos(session('idUser'), session('CodRol'), 'sys/usr/update');
        unset($uyp);
        if ($permiso == false) {
            Log::channel('act')->warning('El usuario ' . auth()->user()->email . ' intento modificar al usuario id: ' . $r->id . ' sin permiso.');
            return $this->listarUsuarios(false, "No tiene permiso para modificar usuarios");
        }

        $user = DB::table('users')->where('id', $r->id)->first();
        $roles = DB::table('sys_roles')->where('activo', 1)->get();
        $rolesOfUser = DB::table('sys_asoc_rol_user')->where('id_user', $r->id)->where('activo', 1)->get();
        $id_roles = [];
        foreach ($rolesOfUser as $rol) {
            $id_roles[] = DB::table('sys_roles')->where('cod_rol', $rol->cod_rol)->value('id');
        }
        //  print_r($id_roles);
        Log::channel('act')->info(auth()->user()->email . ' Visualiza formulario de edición para el usuario  ' . $user->email);
        return view('sigma.users.SgmEditUsuario', compact('roles'))
            ->with('user', $user)
            ->with('id_roles', $id_roles);
    }

    /*
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████   
*/
    public function GuardarEditarUsuario(Request $r)
    {

        $uyp = new UyPController();
        $permiso = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'sys/usr/update');
        unset($uyp);
        if ($permiso == false) {
            Log::channel('act')->warning('En usuario ' . auth()->user()->email . ' intento modificar al usuario id: ' . $r->id . ' - ' . $r->email . ' sin permiso.');
            return $this->listarUsuarios(false, "No tiene permiso para modificar usuarios");
        }

        $rules = [
            'email' =>  ['required', 'email'],
            'nombre' => 'required|string|max:255',
            'rol' => 'required|array|min:0',
        ];

        $mensajes = [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.max' => 'El campo nombre  no debe superar los :max caracteres.',
            'rol.required' => 'El campo área es obligatorio.',
            'rol.min' => 'Debe seleccionar un área.',
            'email.required' => 'El campo de correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.datounico' => 'El formato del correo electrónico ya esta en uso.',
        ];

        $validatedData = $r->validate($rules, $mensajes);
        try {
            DB::table('users')->where('id', $r->id)->update([
                'name' => strtoupper($r->nombre),
                'email' => $r->email
            ]);
        } catch (Exception $e) {
            Log::channel('sistema')->error(__METHOD__ . ' Ln: ' . __LINE__ . ' Error actualizando usuario' . $r->email . ' ' . $e);
            return $this->listarUsuarios(false, "Error al actualizar datos del usuario");
        }
        $this->actualizarRoles($r->id, $r->rol);

        Log::channel('act')->info(auth()->user()->email . ' Actualizó los datos de ' . $r->email . ' correctamente');
        return $this->listarUsuarios(true, "Usuario modificado con éxito");
    }

    /*
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████   
*/
    public function BloquearUsuario(Request $r)
    {
        return 'formulario de bloqueo';
    }
    /*
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████   
*/
    public function GuardarBloqueo(Request $r)
    {
        return 'guardar bloqueo';
    }
    /*
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████   
*/
    public function DesbloquearUsuario(Request $r)
    {
        return ' desbloqueo';
    }

    /*███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
 _                _         ___   _       _            _        █████████████████████████████████████████████████████████████████████████████████████████████████████████
| |              | |       / / | | |     | |          | |       █████████████████████████████████████████████████████████████████████████████████████████████████████████   
| |     ___   ___| | __   / /| | | |_ __ | | ___   ___| | __    █████████████████████████████████████████████████████████████████████████████████████████████████████████
| |    / _ \ / __| |/ /  / / | | | | '_ \| |/ _ \ / __| |/ /    █████████████████████████████████████████████████████████████████████████████████████████████████████████
| |___| (_) | (__|   <  / /  | |_| | | | | | (_) | (__|   <     █████████████████████████████████████████████████████████████████████████████████████████████████████████
\_____/\___/ \___|_|\_\/_/    \___/|_| |_|_|\___/ \___|_|\_\    █████████████████████████████████████████████████████████████████████████████████████████████████████████
                                                                █████████████████████████████████████████████████████████████████████████████████████████████████████████
   ██████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████ */
    public function DeshabilitarUsuario(Request $r)
    {



        $solicitado = $this->solicitado($r->id);
        $uyp = new UyPController();
        $permiso = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'sys/usr/lock');
        unset($uyp);
        if ($permiso == false) {
            Log::channel('act')->warning('En usuario ' . auth()->user()->email . ' intento habilitar o deshabilitar al usuario: ' . $solicitado->email . ' sin permiso.');
            return view('error')->with('error', 'No tiene permiso para habilitar o deshabilitar usuarios');
          
        }


        $solicitante = auth()->user()->name . '(' . auth()->user()->email . ')';
        $user = DB::table('users')->where('id', $r->hidden_id)->first();
        if ($user->activo == 1) {
            $cod = 0;
            $accion = 'Deshabilitado';
            $verbo = 'deshabilitar';
        } else {
            $cod = 1;
            $accion = 'Habilitado';
            $verbo = 'habilitar';
        }
        if ($r->pass1 == $r->pass2 && $this->validarEjecucion($r->pass1) == true) {
            DB::table('users')->where('id', $r->hidden_id)->update([
                'activo' => $cod,
            ]);

            Log::channel('act')->info('El usuario ' . $solicitante . ' ha ' . $accion . ' al usuario ' . $user->name . '(' . $user->email . ').');
            return $this->listarUsuarios(true, 'Se ha ' . $accion . ' el Usuario ' . $user->name . '(' . $user->email . ').');
        } else {
            Log::channel('act')->info('El usuario ' . $solicitante . ' ha intentado ' . $accion . ' al usuario ' . $user->name . '(' . $user->email . ').');
            return $this->listarUsuarios(false, 'No fue posible ' . $verbo . ' el usuario ' . $user->name . '(' . $user->email . ').');
        }
    }

    /*---------------------------------------------------------------------------------------------------------------------------------------------------------------------
 _                              _         _             _               █████████████████████████████████████████████████████████████████████████████████████████████████████████
| |                            | |       | |           (_)              █████████████████████████████████████████████████████████████████████████████████████████████████████████
| |__   ___  _ __ _ __ __ _  __| | ___   | | ___   __ _ _  ___ ___      █████████████████████████████████████████████████████████████████████████████████████████████████████████
| '_ \ / _ \| '__| '__/ _` |/ _` |/ _ \  | |/ _ \ / _` | |/ __/ _ \     █████████████████████████████████████████████████████████████████████████████████████████████████████████
| |_) | (_) | |  | | | (_| | (_| | (_) | | | (_) | (_| | | (_| (_) |    █████████████████████████████████████████████████████████████████████████████████████████████████████████
|_.__/ \___/|_|  |_|  \__,_|\__,_|\___/  |_|\___/ \__, |_|\___\___/     █████████████████████████████████████████████████████████████████████████████████████████████████████████
                                                   __/ |                █████████████████████████████████████████████████████████████████████████████████████████████████████████
                                                  |___/                 █████████████████████████████████████████████████████████████████████████████████████████████████████████
    ---------------------------------------------------------------------------------------------------------------------------------------------------------------------  */
    public function BorrarUsuario(Request $r)
    {
        //invoicrados
        $solicitado = $this->solicitado($r->id);
        $solicitante = auth()->user()->name . '(' . auth()->user()->email . ')';
        //permisos------
        $uyp = new UyPController();
        $permiso = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'sys/usr/delete');
        unset($uyp);
        if ($permiso == false) {
            Log::channel('act')->warning('En usuario ' . auth()->user()->email . ' intento borrar al usuario: ' . $solicitado->email . ' sin permiso.');
            // cambiar por default error
            return view('error')->with('error', 'No tiene permiso para borrar usuarios');
           // return ('No tiene permiso para habilitar o deshabilitar usuarios');
        }

        //Validacion contraseña admin
        $user = DB::table('users')->where('id', $r->hidden_id)->first();
        if ($r->pass1 == $r->pass2 && $this->validarEjecucion($r->pass1) == true) {

            try {

                DB::table('users')->where('id', $r->hidden_id)->update([
                    'borrado' => 1,
                    'activo' => 0,
                ]);
            } catch (Exception $e) {
                Log::channel('sistema')->error(__METHOD__ . ' Ln: ' . __LINE__ . ' Error Borrando logicamente usuario' . $solicitado->email . ' error:' . $e);
            }


            Log::channel('act')->info(' El usuario ' . $solicitante . ' ha borrado al usuario ' . $user->name . '(' . $user->email . ').');
            return $this->listarUsuarios(true, 'Se ha eliminado el Usuario ' . $user->name . '(' . $user->email . ').');
        } else {
            Log::channel('act')->info('El usuario ' . $solicitante . ' ha intentado borrar al usuario ' . $user->name . '(' . $user->email . ').');
            return $this->listarUsuarios(false, "No fue posible borrar el usuario.");
        }
    }

    /*--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
______                          _          __ _     _                       █████████████████████████████████████████████████████████████████████████████████████████████████████████
| ___ \                        | |        / _(_)   (_)                      █████████████████████████████████████████████████████████████████████████████████████████████████████████
| |_/ / ___  _ __ _ __ __ _  __| | ___   | |_ _ ___ _  ___ ___              █████████████████████████████████████████████████████████████████████████████████████████████████████████
| ___ \/ _ \| '__| '__/ _` |/ _` |/ _ \  |  _| / __| |/ __/ _ \             █████████████████████████████████████████████████████████████████████████████████████████████████████████
| |_/ / (_) | |  | | | (_| | (_| | (_) | | | | \__ \ | (_| (_) |            █████████████████████████████████████████████████████████████████████████████████████████████████████████
\____/ \___/|_|  |_|  \__,_|\__,_|\___/  |_| |_|___/_|\___\___/             █████████████████████████████████████████████████████████████████████████████████████████████████████████
                                                                            █████████████████████████████████████████████████████████████████████████████████████████████████████████
                                                                             █████████████████████████████████████████████████████████████████████████████████████████████████████████
-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
    public function  ExtinguirUsuario(Request $r)
    {
        return 'borrado físico';
    }



    /*---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
______                      ___        _                       _     _             
|  ___|                    / _ \      | |                     (_)   | |            ███████████████████████████████████████████████████████████████████████████████████████████████
| |_ ___  _ __ _ __ ___   / /_\ \_   _| |_ ___  _ __ ___  __ _ _ ___| |_ _ __ ___  ███████████████████████████████████████████████████████████████████████████████████████████████
|  _/ _ \| '__| '_ ` _ \  |  _  | | | | __/ _ \| '__/ _ \/ _` | / __| __| '__/ _ \ ███████████████████████████████████████████████████████████████████████████████████████████████
| || (_) | |  | | | | | | | | | | |_| | || (_) | | |  __/ (_| | \__ \ |_| | | (_) |███████████████████████████████████████████████████████████████████████████████████████████████
\_| \___/|_|  |_| |_| |_| \_| |_/\__,_|\__\___/|_|  \___|\__, |_|___/\__|_|  \___/ ███████████████████████████████████████████████████████████████████████████████████████████████
                                                          __/ |                    
                                                         |___/                     
    
    ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
    public function Registrarse(Request $r)
    {
        return ' formulario autoregistro';
    }


    /*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------

 _____                     _               ___        _                       _     _             
|  __ \                   | |             / _ \      | |                     (_)   | |            ███████████████████████████████████████████████████████████████████████████████████████████████
| |  \/_   _  __ _ _ __ __| | __ _ _ __  / /_\ \_   _| |_ ___  _ __ ___  __ _ _ ___| |_ _ __ ___  ███████████████████████████████████████████████████████████████████████████████████████████████
| | __| | | |/ _` | '__/ _` |/ _` | '__| |  _  | | | | __/ _ \| '__/ _ \/ _` | / __| __| '__/ _ \ ███████████████████████████████████████████████████████████████████████████████████████████████
| |_\ \ |_| | (_| | | | (_| | (_| | |    | | | | |_| | || (_) | | |  __/ (_| | \__ \ |_| | | (_) |███████████████████████████████████████████████████████████████████████████████████████████████
 \____/\__,_|\__,_|_|  \__,_|\__,_|_|    \_| |_/\__,_|\__\___/|_|  \___|\__, |_|___/\__|_|  \___/ ███████████████████████████████████████████████████████████████████████████████████████████████
                                                                         __/ |                    
                                                                        |___/                     
-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

    public function GuardarRegistro(Request $r)
    {
        return ' formulario autoregistro';
    }
    /*------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  _   _       _ _     _            _                ___        _                       _     _             
| | | |     | (_)   | |          (_)              / _ \      | |                     (_)   | |            ███████████████████████████████████████████████████████████████████████████████████████████████
| | | | __ _| |_  __| | __ _  ___ _  ___  _ __   / /_\ \_   _| |_ ___  _ __ ___  __ _ _ ___| |_ _ __ ___  ███████████████████████████████████████████████████████████████████████████████████████████████
| | | |/ _` | | |/ _` |/ _` |/ __| |/ _ \| '_ \  |  _  | | | | __/ _ \| '__/ _ \/ _` | / __| __| '__/ _ \ ███████████████████████████████████████████████████████████████████████████████████████████████
\ \_/ / (_| | | | (_| | (_| | (__| | (_) | | | | | | | | |_| | || (_) | | |  __/ (_| | \__ \ |_| | | (_) |███████████████████████████████████████████████████████████████████████████████████████████████
 \___/ \__,_|_|_|\__,_|\__,_|\___|_|\___/|_| |_| \_| |_/\__,_|\__\___/|_|  \___|\__, |_|___/\__|_|  \___/ ███████████████████████████████████████████████████████████████████████████████████████████████
                                                                                 __/ |                    
                                                                                |___/                       
    
    ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
    public function ValidarRegistro(Request $r)
    {
        return 'validar autoregistro';
    }
    /*
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████   
*/
    public function CambiarContra(Request $r)
    {


        ///permisos----------------------------------------------------------------

        $solicitante = auth()->user()->email;
        $solicitado = $this->Solicitado($r->id);


        //permisos
        $uyp = new UyPController();
        $permiso = $uyp->VerificarPermisos(session('idUser'),   session('CodRol'), 'sys/usr/pwdrst');
        unset($uyp);
        if ($permiso == false) {
            Log::channel('act')->warning('En usuario ' . $solicitante . ' intento resetear password de usuario: ' . $solicitado->email . ' sin permiso.');
            return view('error')->with('error', 'No tiene permiso para habilitar o deshabilitar usuarios');
           // return ('No tiene permiso para habilitar o deshabilitar usuarios');
        }

        $nuevaContraseña = $this->generarPasswordAleatorio();

        try {
            DB::table('users')->where('id', $r->id)->update(['password' => bcrypt($nuevaContraseña)]);
            Log::channel('act')->info(auth()->user()->email . ' El usuario ' . $solicitante . ' reseteo la contraseña del Usuario ' . $solicitado->email);
        } catch (Exception $e) {
            Log::channel('sistema')->error(__METHOD__ . ' Ln: ' . __LINE__ . ' Error al resetear contraseña ' . $e);
            return $this->listarUsuarios(false, 'Contraseña Reseteada exitosamente');
        }

        //mandar correo
        /* Logica para correos de prueba*------------------------------------------------------------------------------------------------------------------*/
        $pruebaCorreo = env('MAIL_TEST');
        Log::channel('sistema')->warning(__METHOD__ . ' ln: ' . __LINE__ . ': El modo de email de prueba está establecido en: ' . $pruebaCorreo);

        if ($pruebaCorreo == 'true') {
            $correo = 'mjomiak@gmail.com';
        } else {
            $correo = $solicitado->email;
        }
        Log::channel('sistema')->warning(__METHOD__ . ' ln: ' . __LINE__ . ': se utilizara la direccion: ' . $correo);
        /* -----------------------------------------------------------------------------------------------------------------------------------------------------*/

        try {
            DB::table('users')->where('id', $r->id)->update(['accion' => 1]);
        } catch (Exception $e) {
            Log::channel('sistema')->error(__METHOD__ . ' Ln: ' . __LINE__ . ' Error cambiando la accion a 1 ' . $e);
            return $this->listarUsuarios(false, "No se ha completado el proceso de reset de contraseña.");
        }



        try {
            Mail::to($correo)->send(new ResetContra($solicitado->id, $nuevaContraseña));
        } catch (Exception $e) {
            Log::channel('sistema')->error(__METHOD__ . ' ln: ' . __LINE__ . ': No se ha enviado el correo de alta al usuario:' . $solicitado->email . ', correo: ' . $solicitado->email . 'Error: ' . $e);
            return $this->listarUsuarios(false, "No se ha completado el proceso de reset de contraseña.");
        }


        Log::channel('act')->info(auth()->user()->email . ' ha reseteado la contraseña del usuario ' . $solicitado->email);
        return $this->listarUsuarios(true, 'Contraseña Reseteada exitosamente');
    }

    /*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------
  __                                                _                            
 / _|                                              | |                           ███████████████████████████████████████████████████████████████████████████████████████████████
| |_ ___  _ __ ___ ___   _ __   __ _ ___ ___    ___| |__   __ _ _ __   __ _  ___ ███████████████████████████████████████████████████████████████████████████████████████████████
|  _/ _ \| '__/ __/ _ \ | '_ \ / _` / __/ __|  / __| '_ \ / _` | '_ \ / _` |/ _ \███████████████████████████████████████████████████████████████████████████████████████████████
| || (_) | | | (_|  __/ | |_) | (_| \__ \__ \ | (__| | | | (_| | | | | (_| |  __/███████████████████████████████████████████████████████████████████████████████████████████████
|_| \___/|_|  \___\___| | .__/ \__,_|___/___/  \___|_| |_|\__,_|_| |_|\__, |\___|███████████████████████████████████████████████████████████████████████████████████████████████
                        | |                                            __/ |     
                        |_|                                           |___/     
    --------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
    public function CambiarContraForza(Request $r)
    {

        //validar que tenga los permisos

        try {
            DB::table('users')->where('id', $r->id)->update(['accion' => 1]);
        } catch (Exception $e) {
            return $this->listarUsuarios(false, "No fue posible forzar el cambio de contraseña.");
        }
        return $this->listarUsuarios(true, "Éxito: El usuario deberá cambiar la contraseña cuando inicie sesión");
    }

    public function GuardarNuevaContr(Request $r)
    {
        return 'guardar contraseña';
    }



    /*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 _____                                 ______                _                  ______             
|  __ \                                | ___ \              | |                 | ___ \             █████████████████████████████████████████████████████████████████████████████
| |  \/ ___ _ __   ___ _ __ __ _ _ __  | |_/ /__ _ _ __   __| | ___  _ __ ___   | |_/ /_ _ ___ ___  █████████████████████████████████████████████████████████████████████████████
| | __ / _ \ '_ \ / _ \ '__/ _` | '__| |    // _` | '_ \ / _` |/ _ \| '_ ` _ \  |  __/ _` / __/ __| █████████████████████████████████████████████████████████████████████████████
| |_\ \  __/ | | |  __/ | | (_| | |    | |\ \ (_| | | | | (_| | (_) | | | | | | | | | (_| \__ \__ \ █████████████████████████████████████████████████████████████████████████████
 \____/\___|_| |_|\___|_|  \__,_|_|    \_| \_\__,_|_| |_|\__,_|\___/|_| |_| |_| \_|  \__,_|___/___/ █████████████████████████████████████████████████████████████████████████████
                                                                                                   
                                                                                                  
--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/



    function generarPasswordAleatorio()
    {
        $caracteresPermitidos = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $longitud = 8;
        $passwordAleatorio = '';

        for ($i = 0; $i < $longitud; $i++) {
            $indiceAleatorio = rand(0, strlen($caracteresPermitidos) - 1);
            $caracterAleatorio = $caracteresPermitidos[$indiceAleatorio];
            $passwordAleatorio .= $caracterAleatorio;
        }
        return $passwordAleatorio;
    }

    /*
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████   
*/
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

    /*████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
 _   _____________  ___ _____ _____  ______ _____ _      _____ _____    █████████████████████████████████████████████████████████████████████████████████████████████████████████
| | | | ___ \  _  \/ _ \_   _|  ___| | ___ \  _  | |    |  ___/  ___|   █████████████████████████████████████████████████████████████████████████████████████████████████████████
| | | | |_/ / | | / /_\ \| | | |__   | |_/ / | | | |    | |__ \ `--.    █████████████████████████████████████████████████████████████████████████████████████████████████████████
| | | |  __/| | | |  _  || | |  __|  |    /| | | | |    |  __| `--. \   █████████████████████████████████████████████████████████████████████████████████████████████████████████
| |_| | |   | |/ /| | | || | | |___  | |\ \\ \_/ / |____| |___/\__/ /   █████████████████████████████████████████████████████████████████████████████████████████████████████████
 \___/\_|   |___/ \_| |_/\_/ \____/  \_| \_|\___/\_____/\____/\____/    █████████████████████████████████████████████████████████████████████████████████████████████████████████
                                                                        █████████████████████████████████████████████████████████████████████████████████████████████████████████
███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████*/


    function actualizarRoles($usuario, $rol)
    {

        for ($i = 0; $i < sizeof($rol); $i++) {
            $rol[$i] = $this->getCodRol($rol[$i]);
        }


        $asig = DB::table('sys_asoc_rol_user')
            ->where('id_user', $usuario)
            // ->where('activo', true)
            ->groupBy('cod_rol')
            ->pluck('cod_rol')
            ->toArray();

        //print_r($asig);

        $query = DB::table('sys_asoc_rol_user')
            ->where('id_user', $usuario)
            ->where('activo', true)
            ->groupBy('cod_rol');


        $sql = $this->addBindingToSql($query);
        Log::channel('sistema')->info($sql);

        $todosLosRoles = DB::table('sys_roles')->where('activo', true)->pluck('cod_rol')->toArray();



        $diferencia = array_diff($todosLosRoles, $asig);
        Log::info('al pedo es para un breakpoint');

        foreach ($asig as $a) {

            if (in_array($a, $rol)) {
                //el rol esta asignado y debe estar en activo = true
                DB::table('sys_asoc_rol_user')
                    ->where('id_user', $usuario)
                    ->where('cod_rol', $a)
                    ->update(['activo' => true]);
            } else {
                //el rol estasignado pero debe estar en activo = false
                DB::table('sys_asoc_rol_user')
                    ->where('id_user', $usuario)
                    ->where('cod_rol', $a)
                    ->update(['activo' => false]);
            }
        }
        if (sizeof($diferencia) > 0) {
            foreach ($diferencia as $d) {
                DB::table('sys_asoc_rol_user')->insert([
                    'id_user' => $usuario,
                    'cod_rol' => $d,
                    'activo' => true,
                ]);
            }
        }
    }


    /*
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████   
*/


    function getCodRol($id_rol)
    {
        return DB::table('sys_roles')->where('id', $id_rol)->value('cod_rol');
    }

    /*
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████   
*/
    private function addBindingToSql($query)
    {
        $bindings = $query->getBindings();
        $sql = $query->toSql();

        foreach ($bindings as $binding) {
            $pos = strpos($sql, '?');
            if ($pos !== false) {
                $sql = substr_replace($sql, $binding, $pos, 1);
            }
        }

        return $sql;
    }

    /*
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████   
*/

    function obtenerLineasConString($nombreArchivo, $stringBuscado)
    {
        // Inicializa la variable que contendrá las líneas encontradas
        $lineasEncontradas = [];

        // Abre el archivo en modo lectura
        $archivo = fopen($nombreArchivo, 'r');

        // Verifica si el archivo se abrió correctamente
        if ($archivo) {
            // Lee el archivo línea por línea
            while (($linea = fgets($archivo)) !== false) {
                $linea = rtrim(html_entity_decode($linea)); // Verifica si la línea contiene el string buscado
                if (strpos($linea, $stringBuscado) !== false) {
                    // Agrega la línea al array de líneas encontradas
                    $lineasEncontradas[] = $linea;
                }
            }

            // Cierra el archivo
            fclose($archivo);
        } else {
            // Manejar el caso en que no se pudo abrir el archivo
            echo "No se pudo abrir el archivo: $nombreArchivo";
        }

        // Devuelve el array de líneas encontradas
        return $lineasEncontradas;
    }

    /*
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
    ███████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████   
*/

    public function Solicitado($id)
    {
        try {
            $user = DB::table('users')->where('id', $id)->first();
        } catch (Exception $e) {
            Log::channel('sistema')->info(__METHOD__ . ' Ln: ' . __LINE__ . ' Error al buscar el usuario:' . $e);
        }
        return $user;
    }



public function frmNuevaContra($mensaje=''){
    return view('sigma.newpass')->with('mensaje','');

}
public function GuardarContra(Request $rq){
   if($rq->pass1 == $rq->pass2){ 

try {
    DB::table('users')->where('id',auth()->user()->id)->update(
        [
            'password'=>bcrypt($rq->pass1),
            'accion'=>0
        ]
        );
} catch (Exception $e) {
Log::channel('sistema')->error(__METHOD__.' Ln: '. __LINE__.' Error actualizando contraseña '.$e);
} 
return redirect()->route('logout');

   }else{
    return 'contraseñas NO coinciden';
   }
}



public function MiConfig(){
    return view('sigma.mypanel');
}

}
