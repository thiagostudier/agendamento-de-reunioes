<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use App\Models\User;
use Closure;

class UserAuthenticate
{
    public function handle($request, Closure $next)
    {
        // VERIFICAR SE O USUARIO É ADMIN
        $user = auth()->guard('api')->user() ?? ''; //PEGAR O TIPO DO USUARIO
        if($user){
            return $next($request);
        }
        return redirect('home');
    }

    protected function redirectTo($request)
    {
        if ($request->expectsJson()) {
            return route('login');
        }
    }
}
