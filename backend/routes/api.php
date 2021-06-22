<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function(){
    // LISTAR REUNIÕES
    Route::get('/meetings', 'App\Http\Controllers\MeetingController@index');
    // PEGAR REUNIÃO
    Route::get('/meetings/{id}', 'App\Http\Controllers\MeetingController@show');
    // FILTRAR REUNIÕES
    Route::post('/filter', 'App\Http\Controllers\MeetingController@filter');
    // CRIAR REUNIÃO
    Route::post('/meetings', 'App\Http\Controllers\MeetingController@store');
    // ATUALIZAR REUNIÃO
    Route::put('/meetings/{id}', 'App\Http\Controllers\MeetingController@update');
    // ACEITAR/RECUSAR REUNIÃO
    Route::put('/meetings-accept/{id}', 'App\Http\Controllers\MeetingController@accept');
    // LOGIN
    Route::post('/login', 'App\Http\Controllers\UserController@login');
    // VALIDATE LOGIN
    Route::middleware('auth:api')->post('/get-me', 'App\Http\Controllers\UserController@getMe');
});
