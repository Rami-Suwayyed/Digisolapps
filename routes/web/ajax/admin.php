<?php

use Illuminate\Support\Facades\Route;


Route::prefix("main-categories")->group(function (){

    Route::prefix("/{main_id}/sub-categories")->group(function () {
        Route::name("sub_category.")->group(function (){
            Route::get("/", "SubCategoryController@index")->name("index");
        });
        Route::prefix("/{sub_id}/subjects")->group(function (){
            Route::name("subject.")->group(function (){
                Route::post("/", "SubjectController@store")->name("store");
                Route::get("/", "SubjectController@index")->name("index");
                Route::put("/{id}", "SubjectController@update")->name("update");
            });
        });
    });

});

Route::prefix("/notifications")->group(function(){
    Route::post("/update", "AdminNotificationController@update");
});


Route::prefix("/managers")->group(function(){
    Route::get("/all/change", "ManagerController@index");
    Route::post("/change/update", "ManagerController@changeType");
    Route::post("/change-status", "ManagerController@changeStatus")->name("manager.change_status");

});


Route::prefix("main")->name("categories.")->group(function(){
    Route::post("/change-status", "CategoryAppsAjaxController@change_status")->name("change_status");
});



Route::prefix("dashboard")->name("dashboard.")->group(function (){
    Route::get("/","DashboardController@index")->name("index");
});
