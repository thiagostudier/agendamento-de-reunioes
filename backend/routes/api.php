<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function(){
    // LOGIN
    Route::post('/login', 'App\Http\Controllers\UserController@login');
    // CRIAR REUNIÃO
    Route::post('/meetings', 'App\Http\Controllers\MeetingController@store');
    // LISTAR REUNIÕES
    Route::get('/meetings', 'App\Http\Controllers\MeetingController@get');
    // PEGAR REUNIÃO ESPECÍFICA
    Route::get('/meetings/{id}', 'App\Http\Controllers\MeetingController@show');
    // ROTAS COM AUTENTICAÇÃO
    Route::group(['middleware'=>'auth:api'], function(){
        // VALIDATE LOGIN
        Route::post('/get-me', 'App\Http\Controllers\UserController@getMe');
        // ATUALIZAR REUNIÃO
        Route::patch('/meetings/{id}', 'App\Http\Controllers\MeetingController@update');
        // REMOVER REUNIÃO
        Route::delete('/meetings/{id}', 'App\Http\Controllers\MeetingController@delete');
        // ACEITAR/RECUSAR REUNIÃO
        Route::put('/meetings-accept/{id}', 'App\Http\Controllers\MeetingController@accept');
    });
});
