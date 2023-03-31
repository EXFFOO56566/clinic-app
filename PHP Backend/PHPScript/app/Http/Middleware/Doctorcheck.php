<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Doctorcheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()&&Auth::user()->usertype==3){
            return $next($request);            
        }else{
            return redirect('doctor/');
        }
    }
}
