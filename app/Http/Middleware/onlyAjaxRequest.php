<?php

namespace App\Http\Middleware;

use Closure;

class onlyAjaxRequest {
   
    public function handle($request, Closure $next){
        if(!$request->ajax())
            die("Access denied!");
        return $next($request);
    }
}
