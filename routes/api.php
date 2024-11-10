<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BatallaController;
use App\Http\Controllers\PersonajesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/resistro', 'App\Http\Controllers\AccessController@store');

Route::post('/login', 'App\Http\Controllers\AccessController@login');


Route::post('/logout', 'App\Http\Controllers\AccessController@logout');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/salas', [SalaController::class, 'index']);
    Route::get('/salas/encurso', [SalaController::class, 'en_curso']);
    Route::post('/salas', [SalaController::class, 'crearSala']);
    Route::post('/salas/{uuid}/unirse', [SalaController::class, 'unirseASala']);

    Route::get('/personajes', [PersonajesController::class, 'index']);
    Route::post('/personajes', [PersonajesController::class, 'crearPersonaje']);

    Route::post('/salas/{uuid}/personajes', [PersonajesController::class, 'asignarPersonajeASala']);

    Route::post('/salas/{uuid}/atacar', [BatallaController::class, 'atacar']);

});
