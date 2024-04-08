<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------

    Rutas del modulo principal sigma, al final se incluyen los ficheros 
    de otros modulos instalados.

*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('info', function () {
    return phpinfo();
})->name('info');

Route::get('foo', function () {
    return "ruta de prueba, cambiar en sys_rutas _puertas";
})->name('foo');

Route::get('revisar', function () {
    return "Revisar esta ruta.";
})->name('revisar');

// Comandos
Route::get('/optimize', function () {
    $output = Artisan::call('optimize');
    return "Comando Artisan optimize ejecutado. Salida: <br>" . $output;
});



Auth::routes(); // rutas para login, registro, etc.

Route::group(['middleware' => 'auth'], function () {
    // Otras rutas aquí

    //Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');





Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/SelectRol', [App\Http\Controllers\HomeController::class, 'PostSelectRol'])->name('SelectRol');
Route::get('/MainMenu', [App\Http\Controllers\HomeController::class, 'MainMenu'])->name('MainMenu');


//Usuarios
/*Listado*/
Route::get('/smg/users/config', [App\Http\Controllers\SigmaUsuariosController::class, 'MiConfig'])->name('SigmaConfigUsuarios');
Route::get('/smg/users/list', [App\Http\Controllers\SigmaUsuariosController::class, 'listarUsuarios'])->name('SigmaListadoUsuarios');
/*alta*/
Route::get('/smg/users/new', [App\Http\Controllers\SigmaUsuariosController::class, 'NuevoUsuario'])->name('SigmaUsuarioNuevo');
/*guardar usuario*/
Route::post('/smg/users/save', [App\Http\Controllers\SigmaUsuariosController::class, 'GuardarNuevoUsuario'])->name('SigmaUsuarioGuardar');
/*detalle*/Route::get('/smg/users/detail', [App\Http\Controllers\SigmaUsuariosController::class, 'DetalleUsuario'])->name('SigmaUsuarioDetalle');
/*modificacion formulario*/Route::get('/smg/users/edit', [App\Http\Controllers\SigmaUsuariosController::class, 'EditarUsuario'])->name('SigmaUsuarioEditar');
/*modificacion guardar*/Route::post('/smg/users/edit/save', [App\Http\Controllers\SigmaUsuariosController::class, 'GuardarEditarUsuario'])->name('SigmaUsuarioGuardarEdicion');
/*bloqueo*/Route::get('/smg/users/lock', [App\Http\Controllers\SigmaUsuariosController::class, 'BloquearUsuario'])->name('SigmaUsuarioBloquear');
/*desbloqueo*/
/*inhibicion*/Route::post('/smg/users/inhibit', [App\Http\Controllers\SigmaUsuariosController::class, 'DeshabilitarUsuario'])->name('SigmaUsuarioInhibir');
/*borrado logico*/Route::post('/smg/users/delete', [App\Http\Controllers\SigmaUsuariosController::class, 'BorrarUsuario'])->name('SigmaUsuarioBorrar');
/*borrado logico*/
/*borrado fisico*/
/*autoregistro*/
/*validacion autoregistro*/
/*cambio de contraseña*/ Route::get('/smg/users/pr', [App\Http\Controllers\SigmaUsuariosController::class, 'CambiarContra'])->name('SigmaUsuarioPassReset');
Route::get('/smg/users/fc', [App\Http\Controllers\SigmaUsuariosController::class, 'frmNuevaContra'])->name('SigmaUsuarioCambioPass');
Route::post('/smg/users/savepass', [App\Http\Controllers\SigmaUsuariosController::class, 'GuardarContra'])->name('SigmaUsuarioSavePass');
/*cambio de contrasena forzado*/ Route::get('/smg/users/fpr', [App\Http\Controllers\SigmaUsuariosController::class, 'CambiarContraForza'])->name('SigmaUsuarioForcePassReset');


//permisos
route::get('/ConfigUyP', [App\Http\Controllers\DefinicionesUyPController::class, 'index'])->name('ConfigUyp');
Route::match(['get', 'post'],'/sys/permisos/read',[App\Http\Controllers\SigmaPermisosController::class, 'FormListadoPermisos'])->name('SigmaReadPermisos');
Route::post('/sys/permisos/set',[App\Http\Controllers\SigmaPermisosController::class, 'setPermiso'])->name('SigmaSetPermiso');



Route::get('/get-categorias/{cod_rol}', [App\Http\Controllers\SigmaRolesController::class, 'getCategorias'])->name('getCategorias');
//Route::get('/get-productos/{categoria_id}', 'FormController@getProductos')->name('getProductos');



//roles
Route::get('/sys/roles/read', [App\Http\Controllers\SigmaRolesController::class, 'readRoles'])->name('SigmaRolRead');
Route::get('/sys/roles/create', [App\Http\Controllers\SigmaRolesController::class, 'createRoles'])->name('SigmaRolCreate');
Route::get('/sys/roles/update', [App\Http\Controllers\SigmaRolesController::class, 'updateRoles'])->name('SigmaRolUpdate');
Route::post('/sys/roles/delete', [App\Http\Controllers\SigmaRolesController::class, 'deleteRoles'])->name('SigmaRolDelete');
Route::post('/sys/roles/lock', [App\Http\Controllers\SigmaRolesController::class, 'lockRol'])->name('SigmaRolLock');
Route::post('/sys/roles/save', [App\Http\Controllers\SigmaRolesController::class, 'saveRol'])->name('SigmaRolGuardar');





route::get('/ubk2/trab',[App\Http\Controllers\Ubk2_Trabajadores::class, 'index'])->name('Ubk2ListadoTrab');
route::get('/ubk2/trab/nuevo',[App\Http\Controllers\Ubk2_Trabajadores::class, 'nuevoTrabajador'])->name('Ubk2NuevoTrab');
route::get('/ubk2/trab/baja',[App\Http\Controllers\Ubk2_Trabajadores::class, 'bajaTrabajador'])->name('Ubk2BajaTrab');
route::get('/ubk2/trab/modif/{id}',[App\Http\Controllers\Ubk2_Trabajadores::class, 'modificarTrabajador'])->name('Ubk2ModifTrab');
route::post('/ubk2/trab/update',[App\Http\Controllers\Ubk2_Trabajadores::class, 'updateTrabajador'])->name('Ubk2UpdateTrab');
route::get('/ubk2/trab/lock/{id}',[App\Http\Controllers\Ubk2_Trabajadores::class, 'bloquearTrabajador'])->name('Ubk2LockTrab');
route::get('/ubk2/trab/rd/{id}',[App\Http\Controllers\Ubk2_Trabajadores::class, 'realDeleteTrabajador'])->name('Ubk2RealDeleteTrab');
route::get('/ubk2/trab/load',[App\Http\Controllers\Ubk2_Trabajadores::class, 'CargaMasivaTrabajador'])->name('Ubk2CargaMasivaTrab');

route::post('/ubk2/trab/save',[App\Http\Controllers\Ubk2_Trabajadores::class, 'guardarTrabajador'])->name('Ubk2GuardaTrab');

route::get('ubk2/ges/hhee',[App\Http\Controllers\Ubk2_GestTrab::class, 'HorasExtra'])->name('Ubk2HHEE');
route::get('ubk2/ges/vac',[App\Http\Controllers\Ubk2_GestTrab::class, 'Vacaciones'])->name('Ubk2Vac');
route::get('ubk2/ges/lic',[App\Http\Controllers\Ubk2_GestTrab::class, 'Licencias'])->name('Ubk2Lic');
route::get('ubk2/ges/marcajes',[App\Http\Controllers\Ubk2_GestTrab::class, 'marcajes'])->name('Ubk2GestMarcajes');




Route::get('ubk2/ara/read' ,[App\Http\Controllers\Ubk2_Areas::class, 'listarAreas'])->name('Ubk2ListadoAreas');
Route::get('ubk2/ara/create' ,[App\Http\Controllers\Ubk2_Areas::class, 'FrmCrearArea'])->name('Ubk2CrearAreas');
Route::get('ubk2/ara/update' ,[App\Http\Controllers\Ubk2_Areas::class, 'formEditarArea'])->name('Ubk2EditarArea');
Route::post('ubk2/ara/delete' ,[App\Http\Controllers\Ubk2_Areas::class, 'borrarArea'])->name('Ubk2BorrarArea');
Route::post('ubk2/ara/lock' ,[App\Http\Controllers\Ubk2_Areas::class, 'habilitarArea'])->name('Ubk2BloquearArea');
Route::post('ubk2/ara/save' ,[App\Http\Controllers\Ubk2_Areas::class, 'guardarNuevaArea'])->name('Ubk2GuardarArea');
Route::post('ubk2/ara/saveu' ,[App\Http\Controllers\Ubk2_Areas::class, 'actualizarArea'])->name('Ubk2GuardarEdicionArea');

// cierre grupo rutas para usuarios autenticados

});


Route::get('/miga', [App\Http\Controllers\MigasController::class, 'render'])->name('render');

//Otros Modulos-----------------------------------------
Route::get('ubk2/trn/read' ,[App\Http\Controllers\Ubk2_Turnos::class, 'ListadoTurnos'])->name('Ubk2ListadoTurnos');
Route::get('ubk2/trn/create' ,[App\Http\Controllers\Ubk2_Turnos::class, 'FrmNuevoTurno'])->name('Ubk2FrmNuevoTurno');
Route::get('ubk2/trn/update' ,[App\Http\Controllers\Ubk2_Turnos::class, 'FrmEditarTurno'])->name('Ubk2FrmEditarTurno');
Route::post('ubk2/trn/delete' ,[App\Http\Controllers\Ubk2_Turnos::class, 'BorrarTurno'])->name('Ubk2BorrarTurno');
Route::post('ubk2/trn/save' ,[App\Http\Controllers\Ubk2_Turnos::class, 'GuardarTurno'])->name('Ubk2GuardarTurno');
Route::post('ubk2/trn/edit' ,[App\Http\Controllers\Ubk2_Turnos::class, 'GuardarEdicion'])->name('Ubk2GuardarEdicionTurno');
Route::post('ubk2/trn/lock' ,[App\Http\Controllers\Ubk2_Turnos::class, 'BloquearTurno'])->name('Ubk2BloquearTurno');



//use App\Http\Controllers\YourController;

Route::get('/export-excel', [App\Http\Controllers\Ubk2_Trabajadores::class, 'ExportCMtrab'])->name('ExportCMtrab');
Route::post('/import', [App\Http\Controllers\Ubk2_Trabajadores::class, 'ImportCMtrab'])->name('ImportCMtrab');


Route::view('/Resumen-Carga-Masiva', 'ubk2.trabajadores.resumenCM')->name('ResumenCMTrab');