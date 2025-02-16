<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\SalaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\v1\BatallaController;
use App\Http\Controllers\Api\v1\PersonajesController;

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





Route::prefix('v1')->group(function () {

    Route::post('/resistro', 'App\Http\Controllers\Api\V1\AccessController@store');
    Route::post('/login', 'App\Http\Controllers\Api\V\AccessController@login');
    Route::post('/logout', 'App\Http\Controllers\Api\V1\AccessController@logout');

});



Route::middleware('auth:sanctum')->group(function () {

    route::prefix('v1')->group(function () {
        Route::get('/salas', [SalaController::class, 'index']);
        Route::get('/salas/encurso', [SalaController::class, 'en_curso']);
        Route::post('/salas', [SalaController::class, 'crearSala']);
        Route::post('/salas/{uuid}/unirse', [SalaController::class, 'unirseASala']);
        Route::get('/personajes', [PersonajesController::class, 'index']);
        Route::post('/personajes', [PersonajesController::class, 'crearPersonaje']);
        Route::post('/salas/{uuid}/personajes', [PersonajesController::class, 'asignarPersonajeASala']);
        Route::post('/salas/{uuid}/atacar', [BatallaController::class, 'atacar']);
    });

});

Route::prefix('v2')->group(function () {

    Route::post('/prueba', 'App\Http\Controllers\Api\V2\AccessController@store');

});
