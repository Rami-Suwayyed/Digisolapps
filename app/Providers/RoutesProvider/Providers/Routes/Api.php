<?php


namespace App\Providers\RoutesProvider\Providers\Routes;


use App\Providers\RoutesProvider\Providers\IRoutesProvider;
use Illuminate\Support\Facades\Route;

class Api implements IRoutesProvider
{


    public function mapping($namespace = "App\Http\Controllers\Api")
    {
        Route::group(["prefix" => "api/{lang}/", "middleware" => ["api","lang"] , "namespace" => $namespace, "name" => "api."],function() use ($namespace){
            Route::namespace("Auth")->group(base_path('routes/api/auth.php'));

            //Group Has Auth
            Route::middleware("auth:api")->group(function (){
                Route::group([],base_path('routes/api/main/with_authentication.php'));
                Route::group([],base_path('routes/api/student/with_authentication.php'));
            });

            //teacher app
            Route::prefix("teacher")->namespace("Teacher")->group(function (){
                Route::group([],base_path('routes/api/teacher/without_authentication.php'));
                Route::middleware(["auth:api", "is-teacher", "teacher.is_completed"])->group(base_path('routes/api/teacher/with_authentication.php'));
            });

            Route::group([],base_path('routes/api/main/without_authentication.php'));
            Route::group([],base_path('routes/api/student/without_authentication.php'));
        });



    }
}
