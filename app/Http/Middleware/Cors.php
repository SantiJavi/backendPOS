<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    public function handle(Request $request, Closure $next): Response
    {
        if($request->getMethod() === 'OPTIONS'){
            $response = response('', 200);
            $response->header('Access-Control-Allow-Origin', '*');
            $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        return $response;            
       }       
       
       $response = $next($request);       
       if(method_exists($response,'header')){
           $response->header('Access-Control-Allow-Origin', '*')
           ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
           ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
       }
       return $response;
    }
}
