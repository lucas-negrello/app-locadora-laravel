<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('cliente', 'App\Http\Controllers\ClienteController');
Route::apiResource('carro', 'App\Http\Controllers\CarroController');
Route::apiResource('marca', 'App\Http\Controllers\MarcaController');
Route::apiResource('modelo', 'App\Http\Controllers\ModeloController');
Route::apiResource('locacao', 'App\Http\Controllers\LocacaoController');
