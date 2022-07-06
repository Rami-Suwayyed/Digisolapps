<?php
use Illuminate\Support\Facades\Route;

Route::prefix("digisol")->group(function (){
    Route::post("/ContactUs", "DigisolContactApiController@Contact");
    Route::get("/Apps", "DigisolAppsApiController@index");

    Route::prefix("AboutUs")->group(function (){
        Route::get("/", "DigisolAboutApiController@index");
    });


    Route::prefix("social-media")->group(function (){
        Route::get("/", "DigisolSocialMediaApiController@index");
    });

});

