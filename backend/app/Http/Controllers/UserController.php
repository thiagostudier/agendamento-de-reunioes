<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Meeting;
use App\Http\Requests\MeetingRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //LOGIN
    public function login(Request $request){
        $data = $request->all();
        // VALIDAÇÃO DOS CAMPOS DO CADASTRO
        $validation = Validator::make($data, [
            'email' => 'required|string|email|max:191',
            'password' => 'required|string|min:6|max:191'
        ]);
        // SE HOUVER ERROS, RETORNAR PARA O USUÁRIO
        if($validation->fails()){
            return $validation->errors();
        }
        // TENTAR LOGAR    
        if(Auth::attempt(['email'=>$data['email'], 'password'=>$data['password']])){
            $user = auth()->user();
            $user->token = $user->createToken($user->email)->accessToken;
        }else{
            return ['status'=>false];
        }
        return $user;
    }

    // PEGAR USUÁRIO LOGADO
    public function getMe(Request $request){
         // PEGAR USUÁRIO LOGADO
        $user = $request->user();
        // VERIFICAR SE USUÁRIO É ADMIN
        return $user;
    }

}
