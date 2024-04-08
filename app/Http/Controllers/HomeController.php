<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    private $view;
    private $mensaje;


    public function __construct()
    {
        $this->middleware('auth');
        $this->view = 'home';
        $this->mensaje = "";
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $r)
    {
        //  Log::channel('sistema')->info(__METHOD__.' ln: '.__LINE__.' Ingreso a funcion index hommecontroler');

        if (Auth::user()->accion == 1) {
            Log::channel('act')->info(auth()->user()->email . ' Usuario Debe Cambiar Contraseña');
            return redirect()->route('SigmaUsuarioCambioPass');
        }
        if (Auth::user()->activo == 0) {
            Log::channel('act')->info(auth()->user()->email . ' Usuario Deshabilitado, redireccionar logout');
            $mail = auth()->user()->email;
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['error' => 'El usuario ' . $mail . ' se encuentra bloqueado, por favor, contacte al administrador.']);
        }


        //acá verifico que viene de la navegacion y no del login
        //---- mejora: validar tipo de menu y otras variables de configuracion        
        if (isset($r->nav)) {
            $this->mensaje = "viene del breadcrumb";
            $menu = $this->getMenu(Session('CodRol'));
            $this->mensaje = null;
            return view($this->view, compact('menu'))->with('mensaje', $this->mensaje);
        }
        //revisar la tabla parametros para saber que tipo de menu hay que mostrar

        $id_usr = Auth::user()->id;
        //Establecer variables de sesion
        //buscar el nombre del cliente
        //$nomCliente= DB::table('sys_clientes')->where('id_',$id_usr)->value('nom_cliente');

        $nomCliente = DB::table('sys_clientes')
        ->join('users', 'sys_clientes.id_cliente', '=', 'users.id_cliente')
        ->where('users.id',$id_usr)
        ->select('sys_clientes.*', 'users.*')
        ->first()
        ->nom_cliente;

       

        session([
            'idCliente' => Auth::user()->id,
            'nomCliente'=> $nomCliente,
            'idUser' => Auth::user()->id,
            'NombreCompleto' => Auth::user()->name
        ]);






        // verifico cuantos roles tiene contando los registros que tiene el usuario en 
        // la tabla 

        $cantPerfiles = DB::table('sys_asoc_rol_user')->where('id_user', $id_usr)->where('activo', 1)->count();
        //Log::channel('sistema')->info('El usuario con id: '.$id_usr.' tiene '.$cantPerfiles.' perfiles.');

        if ($cantPerfiles > 1) {
            Log::channel('sistema')->info('lanzo seleccion de perfil '.$cantPerfiles);
            $perfiles = $this->listarRoles();
            return view('auth.selectRol', compact('perfiles'))->with('mensaje', $this->mensaje);
        } elseif ($cantPerfiles == 1) {
            Log::channel('act')->info(auth()->user()->email . ' Entró a su único perfil: ' . Session('cod_rol'));
            session([
                'CodRol' => $this->getCodRolByUserId(Auth::user()->id),
                'NombreRol' => $this->getNombreRolByCodRol($this->getCodRolByUserId(Auth::user()->id)),
            ]);
            Log::channel('act')->info(auth()->user()->email . ' Entró a su único perfil: ' . Session('cod_rol'));
            $menu = $this->getMenu(Session('CodRol'));
            return view($this->view, compact('menu'))->with('mensaje', $this->mensaje);
        } elseif ($cantPerfiles < 1) {
            Log::channel('sistema')->info(__METHOD__ . ' Ln: ' . __LINE__ . ' Error con perfiles de usuario, usuario no tiene perfiles asociados');
            $menu = null;
            $this->mensaje = "Ud no tiene perfiles asignados, por favor, contacte al administrador";
            return view($this->view, compact('menu'))->with('mensaje', $this->mensaje);
        }
    }



    public function PostSelectRol(Request $r)
    {
        $cod_rol = $r->cod_rol;
        session([
            'CodRol' => $cod_rol,
            'NombreRol' => $this->getNombreRolByCodRol($cod_rol),
        ]);
        /*   $menu = $this->getMenu(Session('CodRol'));
     Log::channel('act')->info( auth()->user()->email.' seleccionò el perfil: '.$cod_rol);
        return view('mainmenu', compact('menu'))->with('mensaje', $this->mensaje);
        */
        Log::channel('act')->info(auth()->user()->email . ' seleccionò el perfil: ' . $cod_rol);
        return redirect()->route('MainMenu');
    }


    public function MainMenu()
    {
        $menu = $this->getMenu(Session('CodRol'));

        return view('mainmenu', compact('menu'))->with('mensaje', $this->mensaje);
    }



    public function getCodRolByUserId($userId)
    {
        return DB::table('sys_asoc_rol_user')->where('id_user', $userId)->value('cod_rol');
    }

    public function getNombreRolByCodRol($cod_rol)
    {
        return DB::table('sys_roles')->where('cod_rol', $cod_rol)->value('nombre');
    }

    public function listarRoles()
    {
        $id_usr = session('idUser');
    
        $perfilesUsuario = DB::table('sys_asoc_rol_user')->where('id_user', $id_usr)->where('activo', true)->pluck('cod_rol');
        $perfiles = DB::table('sys_roles')->where('activo', true)->whereIn('cod_rol', $perfilesUsuario)->get();
        //Log::channel('sistema')->info($perfiles);
        //dd($perfiles);
        return $perfiles;
    }



    public function getMenu($cod_rol)
    {
        //Log::channel('sistema')->info('crear menu para el rol codigo '.$cod_rol);
      //  $id_cliente=session('idCliente');
        $menu = DB::table('sys_mainmenu')->where('cod_rol', $cod_rol)->where('visible',1)->get();

        foreach ($menu as $item) {
            $item->ruta = $this->ConfirmPuertaRol($item->puerta, $cod_rol);
            // $item->ruta='giiil';
        }

        return $menu;
    }


    public function ConfirmPuertaRol($puerta, $cod_rol)
    {
        //->where('permitido',true)

        $permitido = DB::table('sys_def_permisos')->where('puerta', $puerta)->where('cod_rol', $cod_rol)->first();
        if ($permitido === null) {
            Log::channel('sistema')->info('no existe la puerta ' . $puerta . ' para el perfil ' . $cod_rol . ' en la tabla SYS_DEF_PERMISOS');
            return '#';
        } else {
            if ($permitido->permitido == true) {
                $ruta = DB::table('sys_rutas_puertas')->where('puerta', $puerta)->value('ruta');
                // dd($ruta);
                if ($ruta === null) {
                    Log::channel('sistema')->info('Revisar registro para la ' . $puerta . ' en la tabla SYS_RUTAS_PUERTAS');
                    return '#';
                } else {
                    return $ruta;
                }
            } {
                return '#';
            }
        }
        //busco si la puerta esta en permisos, si esta busco la ruta
        //si no esta la ruta es # que se mostrara deshabilitado 
    }



    public function getTipoMenu()
    {
        //revisar la tabla parametros para saber que tipo de menu hay que mostrar
        $tipoMenu = DB::table('sys_params')->where('clave', 'tipoMenu')->value('valor');
        //lom : lista ordenada multinivel
        //nvn navegacion multinivel
        //mlm: menu lateral multinivel
        $mensaje = 'nada que decir';

        switch ($tipoMenu) {
            case 'lom':
                $view = 'home';
                $mensaje = 'Lista multi nivel';
                break;
            case 'nvm':
                $view = 'home';
                $mensaje = 'Lista multi nivel';
                break;
            case 'mlm':
                $view = 'home';
                $mensaje = 'menu lateral multi nivel';
            default:
                $view = 'home';
                $mensaje = 'Lista Multi Nivel DEFAULT';
        }
    }
}
