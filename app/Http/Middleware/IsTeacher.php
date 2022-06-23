<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse\Json\JsonResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsTeacher
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
        if(Auth::guard("api")->check()){
            $user = Auth::guard("api")->user();
            if($user->type !== "u")
                return $next($request);
            else
                return JsonResponse::error()->message("inaccessible")->changeCode(408)->changeStatusNumber("S408")->send();
        }else{
            return JsonResponse::error()->message("unauthenticated")->changeCode(401)->changeStatusNumber("S401")->send();
        }
    }
}
