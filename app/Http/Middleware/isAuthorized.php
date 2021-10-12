<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isAuthorized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(isset(getallheaders()['token']) && getallheaders()['token']=="123456") {
            return $next($request);
        }else{
            return response()->json(['status' => false,'error' => "Invalid requst"], 503);
        }
    }
}
