<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class is_admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token=$request->cookie('token');
        $result=JWTToken::VerifyToken($token);
        
        if($result == "unauthorized"){
            return redirect('/userLogin');
        }else if($result->userRole == 'admin'){
            return $next($request);
        }else{
            return redirect('/userLogin');
        }
    }
}
