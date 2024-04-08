<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SigmaPermisosController extends Controller
{
    public function FormListadoPermisos(Request $rq = null)
    {
        $roles = DB::table('sys_roles')->where('borrado', 0)->get();

        if (isset($_POST['rol'])) {
            $cod_rol = $_POST['rol'];
            if ($_POST['categoria'] == "all") {
                $permisos = DB::table('sys_def_permisos')->where('cod_rol', $cod_rol)->get();
                $mensaje_filtro='Mostrando todos los permisos del rol '.$_POST['rol'];
            } else {
                $permisos = DB::table('sys_def_permisos')->where('cod_rol', $cod_rol)->where('categoria', $_POST['categoria'])->get();
                $mensaje_filtro='Mostrando los permisos del rol '.$_POST['rol'].' filtrados por la categoria '.$_POST['categoria'];
            }
           
            return view('sigma.permisos.SigmaListadoPermisos', compact('roles', 'permisos'))->with('mensaje_filtro',$mensaje_filtro);
        } else {
            return view('sigma.permisos.SigmaListadoPermisos', compact('roles'));
        }
    }

    public function setPermiso(Request $rq)
    {
        //return 'id: '.$rq->id.', Estado: '.$rq->estado;
        if ($rq->estado == true) {
            $permitido = 1;
        } else {
            $permitido = 0;
        }
        DB::table('sys_def_permisos')->where('id', $rq->id)->update(['permitido' => $permitido]);
        return;
    }
}
