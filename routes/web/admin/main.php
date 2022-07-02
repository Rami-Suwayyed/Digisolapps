<?php

use Illuminate\Support\Facades\Route;


Route::get("/FirstLogin","DashboardController@FirstLogin")->name("first.login");
Route::put("/Completed", "DashboardController@Completed")->name("login.completed");
Route::group(["middleware" => ['manager']],function (){
    Route::get("/","DashboardController@index")->name("dashboard.index");
//Category CRUD




//Settings
    Route::prefix("/settings")->name("settings.")->group(function (){
        Route::get("/", "SettingsController@index")->name("index");
        Route::post("/save", "SettingsController@save")->name("save");
    });

//Roles Permissions
    Route::resource("/roles", "RoleController")->except("show");
//Managers
    Route::resource("/managers", "ManagerController")->except("show");
    Route::post("/managers/excel", "ManagerController@exportExcelFile")->name("managers.export");



/// terms
    Route::prefix("terms")->name("terms.")->group(function (){
        Route::get("/terms","TermsController@index")->name("index");
        Route::get("/edit/{id}","TermsController@edit")->name("edit");
        Route::get("/create", "TermsController@create")->name("create");
        Route::post("/store","TermsController@store")->name("store");
        Route::put("/update/{id}","TermsController@update")->name("update");
        Route::delete("/destroy/{id}","TermsController@destroy")->name("destroy");
    });


// Sliders
    Route::resource("sliders", "SliderController")->except("show");

// About As Route
    Route::prefix("about")->name("about.")->group(function (){
        Route::get("/", "AboutAsController@index")->name("index");
        Route::get("/create", "AboutAsController@create")->name("create");
        Route::post("/", "AboutAsController@store")->name("store");
        Route::get("/{id}", "AboutAsController@edit")->name("edit");
        Route::post("/{id}", "AboutAsController@update")->name("update");
    });

// instructions and laws Route
    Route::prefix("instructions")->name("instructions.")->group(function (){
        Route::get("/", "InstructionsAndLawsController@index")->name("index");
        Route::get("/create", "InstructionsAndLawsController@create")->name("create");
        Route::post("/", "InstructionsAndLawsController@store")->name("store");
        Route::get("/{id}", "InstructionsAndLawsController@edit")->name("edit");
        Route::post("/{id}", "InstructionsAndLawsController@update")->name("update");
        Route::delete("/{id}", "InstructionsAndLawsController@destroy")->name("destroy");
    });


    Route::prefix("profile")->name("profile.")->group(function(){
        Route::get("/", "ProfileController@index")->name("index");
        Route::get("/Setting", "ProfileController@Setting")->name("Setting");
        Route::put("/update", "ProfileController@update")->name("update");
//    Route::get("/FirstLogin","ProfileController@FirstLogin")->name("firstlogin");
    });

// Website digisol Route
    Route::prefix("digisol")->name("digisol.")->group(function (){
        Route::get("/", "DigisolController@index")->name("index");
        // How home Route
        Route::prefix("/home")->name("home.")->group(function (){
            Route::get("/", "DigisolHomeController@index")->name("index");

            //title Route
            Route::prefix("/title")->name("title.")->group(function (){
                Route::get("/", "DigisolHomeController@indexTitle")->name("index");
                Route::get("/create", "DigisolHomeController@CreateTitle")->name("create");
                Route::post("/", "DigisolHomeController@storeTitle")->name("store");
                Route::get("/{id}", "DigisolHomeController@editTitle")->name("edit");
                Route::post("/{id}", "DigisolHomeController@updateTitle")->name("update");
                Route::delete("/{id}", "DigisolHomeController@destroyTitle")->name("destroy");
            });
                //SecondParagraph Route
                Route::prefix("/SecondParagraph")->name("SecondParagraph.")->group(function (){
                    Route::get("/", "DigisolHomeController@indexSecondParagraph")->name("index");
                    Route::get("/create", "DigisolHomeController@CreateSecondParagraph")->name("create");
                    Route::post("/", "DigisolHomeController@storeSecondParagraph")->name("store");
                    Route::get("/{id}", "DigisolHomeController@editSecondParagraph")->name("edit");
                    Route::post("/{id}", "DigisolHomeController@updateSecondParagraph")->name("update");
                    Route::delete("/{id}", "DigisolHomeController@destroySecondParagraph")->name("destroy");
                });
               //Testimonials Route
               Route::prefix("/body")->name("body.")->group(function (){
                Route::get("/", "DigisolHomeController@indexBody")->name("index");
                Route::get("/create", "DigisolHomeController@CreateBody")->name("create");
                Route::post("/", "DigisolHomeController@storeBody")->name("store");
                Route::get("/{id}", "DigisolHomeController@editBody")->name("edit");
                Route::post("/{id}", "DigisolHomeController@updateBody")->name("update");
                Route::delete("/{id}", "DigisolHomeController@destroyBody")->name("destroy");
            });

        });

    // Digisol Social Media Route
    Route::prefix("social")->name("social.")->group(function (){
        Route::get("/", "DigisolSocialController@index")->name("index");
        Route::get("/create", "DigisolSocialController@create")->name("create");
        Route::post("/", "DigisolSocialController@store")->name("store");
        Route::get("/{id}","DigisolSocialController@edit")->name("edit");
        Route::post("/{id}", "DigisolSocialController@update")->name("update");
        Route::delete("/{id}", "DigisolSocialController@destroy")->name("destroy");
    });
       // Digisol Contact Route
    Route::prefix("contact")->name("contact.")->group(function (){
        Route::get("/","DigisolContactController@index")->name("index");
        Route::get("/create", "DigisolContactController@create")->name("create");
        Route::post("/","DigisolContactController@store")->name("store");
        Route::get("/{id}","DigisolContactController@edit")->name("edit");
        Route::put("/{id}","DigisolContactController@update")->name("update");
        Route::delete("/{id}","DigisolContactController@destroy")->name("destroy");
    });

      //Settings
      Route::prefix("/settings")->name("settings.")->group(function (){
        Route::get("/", "DigisolSettingsController@index")->name("index");
        Route::post("/save", "DigisolSettingsController@save")->name("save");
    });

    Route::prefix("Apps")->name("apps.")->group(function (){
        Route::get("/","DigisolAppsController@index")->name("index");
        Route::get("/create", "DigisolAppsController@create")->name("create");
        Route::post("/","DigisolAppsController@store")->name("store");
        Route::get("/{id}","DigisolAppsController@edit")->name("edit");
        Route::get("Show/{id}","DigisolAppsController@Show")->name("show");
        Route::put("/{id}","DigisolAppsController@update")->name("update");
        Route::delete("/{id}","DigisolAppsController@destroy")->name("destroy");
    });

        Route::prefix("Services")->name("Services.")->group(function (){
            Route::get("/","WebsiteHowWeWorkController@index")->name("index");
            Route::get("/create", "WebsiteHowWeWorkController@create")->name("create");
            Route::post("/","WebsiteHowWeWorkController@store")->name("store");
            Route::get("/{id}","WebsiteHowWeWorkController@edit")->name("edit");
            Route::put("/{id}","WebsiteHowWeWorkController@update")->name("update");
            Route::delete("/{id}","WebsiteHowWeWorkController@destroy")->name("destroy");
        });


    });

    Route::prefix("Notification")->name("Notification.")->group(function () {
        Route::get("/SendForCustom", "NotificationsController@ShowSendForCustom")->name("SendForCustom");
        Route::get("/SendForAll", "NotificationsController@ShowSendForAll")->name("SendForAll");
        Route::get("/sendForService", "NotificationsController@ShowForService")->name("SendForService");
        Route::post("/send", "NotificationsController@send")->name("send");
        Route::post("/sendToCustomService", "NotificationsController@sendToCustomService")->name("sendToCustomService");
        Route::get("/clear", "NotificationsController@clear")->name("clear");
        Route::get("/show/{id}", "NotificationsController@ShowClear")->name("show.clear");
    });


    Route::get("/SendEmail/{id}", "ManagerController@SendEmail")->name("SendEmail");

});

