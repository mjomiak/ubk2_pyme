
<?php

//Rutas pruebas varias

use Illuminate\Support\Facades\Route;

route::get('/testing/marcaje',[App\Http\Controllers\testController::class, 'frm_marcaje'])->name('tstMarcaje');
route::post('/testing/saveMarcaje',[App\Http\Controllers\testController::class, 'save_marcaje'])->name('tstSaveMarcaje');




Route::get('sys_param', function () {
    return 'PARAMETROS';
});
Route::get('sys_users', function () {
    return 'USUARIOS';
})->name('/test/nada');