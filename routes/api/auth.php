<?php

use Illuminate\Support\Facades\Route;

Route::post("login/{type}", "LoginController@login");
Route::post("login-test", "LoginController@testLogin");
Route::post("logout/{type?}", "LoginController@logout")->middleware("auth:api");

Route::post("register", "RegisterController@register");
