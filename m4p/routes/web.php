<?php

use App\Http\Controllers\AgendasController;
use App\Http\Controllers\AtencionesController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\InformesController;
use App\Http\Controllers\PersonasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/reservar/{cm}', [AgendasController::class,'solicitar'])->name('solicitar.hora');
Route::get('/muestra_horas/{id}/{fecha}',[AgendasController::class,'muestra_horas'])->name('agenda.muestra_horas');
Route::post('/agenda/reservar',[AgendasController::class,'reservar'])->name('agenda.reservar');
Route::get('/datos_paciente/{rut}/{cm}',[AgendasController::class,'datosPaciente'])->name('agenda.datos_paciente');

Route::get('/dashboard', [AgendasController::class,'tabla'])->middleware(['auth'])->name('dashboard');

/** agenda **/
Route::get('/agenda', [AgendasController::class,'lista'])->middleware(['auth'])->name('agenda');
Route::get('/agenda/crear',[AgendasController::class,'crear'])->middleware(['auth'])->name('agenda.crear');
Route::post('/agenda/agregar',[AgendasController::class,'agregar'])->middleware(['auth'])->name('agenda.agregar');
Route::get('/agenda/editar/{id}',[AgendasController::class,'editar'])->middleware(['auth'])->name('agenda.editar');
Route::post('/agenda/modificar',[AgendasController::class,'modificar'])->middleware(['auth'])->name('agenda.modificar');
Route::get('/agenda/datos/{id}',[AgendasController::class,'datosAgenda'])->middleware(['auth'])->name('agenda.datos');
Route::get('/agenda/bloqueos', [AgendasController::class,'bloqueos'])->middleware(['auth'])->name('agenda.bloqueos');
Route::post('/agenda/bloquear',[AgendasController::class,'bloquear'])->middleware(['auth'])->name('agenda.bloquear');
Route::get('/agenda/citas/{id}',[AgendasController::class,'lista'])->middleware(['auth'])->name('agenda.citas');
Route::get('/agenda/actualizar/{id}/{estado}',[AgendasController::class,'cambiaEstado'])->middleware(['auth'])->name('agenda.actualizar');
Route::get('/agenda/horas/{id}/{formato}/{seleccionada}',[AgendasController::class,'getHoras'])->middleware(['auth'])->name('agenda.horas');
Route::get('/agenda/dias_bloqueados/{id}',[AgendasController::class,'dias_bloqueados'])->middleware(['auth'])->name('agenda.dias_bloqueados');


Route::get('bloqueos',[AgendasController::class,'listaBloqueos'])->middleware(['auth'])->name('bloqueos.lista');
Route::get('bloqueos/eliminar/{id}',[AgendasController::class,'eliminar'])->middleware(['auth'])->name('bloqueos.eliminar');



/** personas **/
Route::get('/personas/',[PersonasController::class,'lista'])->middleware(['auth'])->name('personas.lista');
Route::get('/personas/crear',[PersonasController::class,'crear'])->middleware(['auth'])->name('personas.crear');
Route::post('/personas/agregar',[PersonasController::class,'agregar'])->middleware(['auth'])->name('personas.agregar');
Route::get('/personas/editar/{id}',[PersonasController::class,'editar'])->middleware(['auth'])->name('personas.editar');
Route::post('/personas/modificar',[PersonasController::class,'modificar'])->middleware(['auth'])->name('personas.modificar');
Route::get('/personas/eliminar/{id}',[PersonasController::class,'eliminar'])->middleware(['auth'])->name('personas.eliminar');
Route::get('/personas/activar/{id}',[PersonasController::class,'activar'])->middleware(['auth'])->name('personas.activar');


/** atenciones **/
Route::get('/atenciones/',[AtencionesController::class,'lista'])->middleware(['auth'])->name('atenciones.lista');
Route::get('/atenciones/paciente/{id}',[AtencionesController::class,'lista_paciente'])->middleware(['auth'])->name('atenciones.lista_paciente');
Route::get('/atenciones/ver/{id}',[AtencionesController::class,'ver'])->middleware(['auth'])->name('atenciones.ver');
Route::get('/atenciones/ver_paciente/{id}',[AtencionesController::class,'ver_paciente'])->middleware(['auth'])->name('atenciones.ver_paciente');
Route::get('/atender/{id}',[AtencionesController::class,'atender'])->middleware(['auth'])->name('atenciones.atender');
Route::post('/atender/guardar',[AtencionesController::class,'guardar'])->middleware(['auth'])->name('atenciones.guardar');
Route::get('atenciones/pdf/{id}', [AtencionesController::class, 'generarPDF'])->name('documentos.pdf');
Route::get('atenciones/receta/{id}', [AtencionesController::class, 'receta'])->name('documentos.receta');
Route::get('atenciones/enviar_receta/{id}', [AtencionesController::class, 'enviar'])->name('documentos.enviar');

/** documentos **/
Route::get('/documentos/',[DocumentosController::class,'lista'])->middleware(['auth'])->name('documentos.lista');
Route::get('/documentos/crear',[DocumentosController::class,'crear'])->middleware(['auth'])->name('documentos.crear');
Route::post('/documentos/agregar',[DocumentosController::class,'agregar'])->middleware(['auth'])->name('documentos.agregar');
Route::get('/documento/eliminar/{id}/{docto}',[DocumentosController::class,'eliminar_documento'])->middleware(['auth'])->name('documentos.eliminar');

/** informes **/
Route::get('/informes/',[InformesController::class,'lista'])->middleware(['auth'])->name('informes.lista');
Route::get('/informes/crear',[InformesController::class,'crear'])->middleware(['auth'])->name('informes.crear');
Route::post('/informes/agregar',[InformesController::class,'agregar'])->middleware(['auth'])->name('informes.agregar');
Route::get('/documento/editar/{id}',[InformesController::class,'editar'])->middleware(['auth'])->name('informes.editar');
Route::post('/informes/modificar',[InformesController::class,'modificar'])->middleware(['auth'])->name('informes.modificar');
Route::get('/informes/ver/{id}',[InformesController::class,'ver'])->middleware(['auth'])->name('informes.ver');
Route::get('/informes/enviar/{id}',[InformesController::class,'enviar'])->middleware(['auth'])->name('informes.enviar');
Route::get('/informes/eliminar/{id}',[InformesController::class,'eliminar'])->middleware(['auth'])->name('informes.eliminar');



require __DIR__.'/auth.php';
