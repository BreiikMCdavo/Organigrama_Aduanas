<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServidorPublicoController;
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

Route::resource('servidores', ServidorPublicoController::class);
