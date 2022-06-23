<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse\Json\JsonResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompletedTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::guard("api")->check()){
            $user = Auth::guard("api")->user();
            if($user->type === "t"){
                if($user->teacher->completed)
                    return $next($request);
            }
        }
        return JsonResponse::error()->message("not completed")->changeCode(408)->changeStatusNumber("S4009")->send();
    }
}
