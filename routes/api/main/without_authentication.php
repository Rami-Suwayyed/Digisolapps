<?php
use Illuminate\Support\Facades\Route;

Route::prefix("digisol")->group(function (){
    Route::post("/ContactUs", "DigisolContactApiController@Contact");
    Route::get("/Apps", "DigisolAppsApiController@index");

    Route::prefix("AboutUs")->group(function (){
        Route::get("/", "DigisolAboutApiController@index");
    });

    Route::prefix("Services")->group(function (){
        Route::get("/", "DigisolServicesApiController@index");
    });

    Route::prefix("social-media")->group(function (){
        Route::get("/", "DigisolSocialMediaApiController@index");
    });

    Route::prefix("home")->group(function (){
        Route::get("/", "DigisolHomeApiController@index");
    });


});

Route::prefix("KadyTech")->group(function (){
    Route::post("/ContactUs", "KadyTechContactApiController@Contact");

    Route::prefix("social-media")->group(function (){
        Route::get("/", "KadyTechSocialMediaApiController@index");
    });
    Route::prefix("home")->group(function (){
        Route::get("/", "KadyTechHomeApiController@index");
    });

});

