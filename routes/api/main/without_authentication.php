<?php
use Illuminate\Support\Facades\Route;

Route::prefix("digisol")->group(function (){
    Route::post("/ContactUs", "DigisolContactApiController@Contact");
    Route::get("/Apps", "DigisolAppsApiController@index");



    Route::prefix("social-media")->group(function (){
        Route::get("/", "DigisolSocialMediaApiController@index");
    });

});

