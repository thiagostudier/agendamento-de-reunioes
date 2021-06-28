<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function(){
    // LOGIN
    Route::post('/login', 'App\Http\Controllers\UserController@login');
    // VALIDATE LOGIN
    Route::middleware('auth:api')->post('/get-me', 'App\Http\Controllers\UserController@getMe');
    // CRIAR REUNIÃO
    Route::post('/meetings', 'App\Http\Controllers\MeetingController@store');
    // LISTAR REUNIÕES
    Route::get('/meetings', 'App\Http\Controllers\MeetingController@index');
    // PEGAR REUNIÃO
    Route::get('/meetings/{id}', 'App\Http\Controllers\MeetingController@show');
    // ATUALIZAR REUNIÃO
    Route::middleware('auth:api')->patch('/meetings/{id}', 'App\Http\Controllers\MeetingController@update');
    // REMOVER REUNIÃO
    Route::middleware('auth:api')->delete('/meetings/{id}', 'App\Http\Controllers\MeetingController@delete');
    // ACEITAR/RECUSAR REUNIÃO
    Route::middleware('auth:api')->put('/meetings-accept/{id}', 'App\Http\Controllers\MeetingController@accept');
});
