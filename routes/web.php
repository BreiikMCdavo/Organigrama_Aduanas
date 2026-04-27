<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServidorPublicoController;
use App\Http\Controllers\OrganigramaController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', function () {
    return redirect()->route('index');
});

Route::get('/index', function () {
    return view('index');
})->name('index');

Route::get('/organigrama/{area}', [OrganigramaController::class, 'info'])
    ->where('area', '.*');
Route::resource('servidores', ServidorPublicoController::class);

// Rutas para reportes
Route::get('/reporte/items', [ServidorPublicoController::class, 'reporteItems'])->name('reporte.items');
Route::get('/reporte/consultoria', [ServidorPublicoController::class, 'reporteConsultoria'])->name('reporte.consultoria');
Route::get('/reporte/acefalias', [ServidorPublicoController::class, 'reporteAcefalias'])->name('reporte.acefalias');
Route::get('/reporte/items/pdf', [ServidorPublicoController::class, 'reporteItemsPdf'])->name('reporte.items.pdf');
Route::get('/reporte/consultoria/pdf', [ServidorPublicoController::class, 'reporteConsultoriaPdf'])->name('reporte.consultoria.pdf');
Route::get('/reporte/acefalias/pdf', [ServidorPublicoController::class, 'reporteAcefaliasPdf'])->name('reporte.acefalias.pdf');

// Rutas para reportes individuales por unidad
Route::get('/reporte/unidad/{nombre}', [ServidorPublicoController::class, 'reportePorUnidad'])->name('reporte.unidad');
Route::get('/reporte/unidad/{nombre}/pdf', [ServidorPublicoController::class, 'reportePorUnidadPdf'])->name('reporte.unidad.pdf');

