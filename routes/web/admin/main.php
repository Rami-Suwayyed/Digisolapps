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
                Route::prefix("/about")->name("about.")->group(function (){
                    Route::get("/", "DigisolAboutController@index")->name("index");
                    //title Route
                    Route::prefix("/first")->name("first.")->group(function (){
                        Route::get("/", "DigisolAboutController@indexFirst")->name("index");
                        Route::get("/create", "DigisolAboutController@CreateFirst")->name("create");
                        Route::post("/", "DigisolAboutController@storeFirst")->name("store");
                        Route::get("/{id}", "DigisolAboutController@editFirst")->name("edit");
                        Route::post("/{id}", "DigisolAboutController@updateFirst")->name("update");
                        Route::delete("/{id}", "DigisolAboutController@destroyFirst")->name("destroy");
                    });
                    //SecondParagraph Route
                    Route::prefix("/secondParagraph")->name("second.")->group(function (){
                        Route::get("/", "DigisolAboutController@indexSecond")->name("index");
                        Route::get("/create", "DigisolAboutController@CreateSecond")->name("create");
                        Route::post("/", "DigisolAboutController@storeSecond")->name("store");
                        Route::get("/{id}", "DigisolAboutController@editSecond")->name("edit");
                        Route::post("/{id}", "DigisolAboutController@updateSecond")->name("update");
                        Route::delete("/{id}", "DigisolAboutController@destroySecond")->name("destroy");
                    });
                    //Testimonials Route
                    Route::prefix("/third")->name("third.")->group(function (){
                        Route::get("/", "DigisolAboutController@indexThird")->name("index");
                        Route::get("/create", "DigisolAboutController@CreateThird")->name("create");
                        Route::post("/", "DigisolAboutController@storeThird")->name("store");
                        Route::get("/{id}", "DigisolAboutController@editThird")->name("edit");
                        Route::post("/{id}", "DigisolAboutController@updateThird")->name("update");
                        Route::delete("/{id}", "DigisolAboutController@destroyThird")->name("destroy");
                    });
                    //Testimonials Route
                    Route::prefix("/fourth")->name("fourth.")->group(function (){
                        Route::get("/", "DigisolAboutController@indexFourth")->name("index");
                        Route::get("/create", "DigisolAboutController@CreateFourth")->name("create");
                        Route::post("/", "DigisolAboutController@storeFourth")->name("store");
                        Route::get("/{id}", "DigisolAboutController@editFourth")->name("edit");
                        Route::post("/{id}", "DigisolAboutController@updateFourth")->name("update");
                        Route::delete("/{id}", "DigisolAboutController@destroyFourth")->name("destroy");
                    });
        });
        Route::prefix("/Services")->name("Services.")->group(function (){
            Route::get("/", "DigisolServicesController@index")->name("index");
            //title Route
            Route::prefix("/mobile")->name("mobile.")->group(function (){
                Route::get("/", "DigisolServicesController@indexMobile")->name("index");
                Route::get("/create", "DigisolServicesController@CreateMobile")->name("create");
                Route::post("/", "DigisolServicesController@StoreMobile")->name("store");
                Route::get("/{id}", "DigisolServicesController@EditMobile")->name("edit");
                Route::post("/{id}", "DigisolServicesController@UpdateMobile")->name("update");
                Route::delete("/{id}", "DigisolServicesController@DestroyMobile")->name("destroy");
            });
            //SecondParagraph Route
            Route::prefix("/web")->name("Web.")->group(function (){
                Route::get("/", "DigisolServicesController@indexWeb")->name("index");
                Route::get("/create", "DigisolServicesController@CreateWeb")->name("create");
                Route::post("/", "DigisolServicesController@storeWeb")->name("store");
                Route::get("/{id}", "DigisolServicesController@editWeb")->name("edit");
                Route::post("/{id}", "DigisolServicesController@UpdateWeb")->name("update");
                Route::delete("/{id}", "DigisolServicesController@destroyWeb")->name("destroy");
            });
            //Testimonials Route
            Route::prefix("/market")->name("market.")->group(function (){
                Route::get("/", "DigisolServicesController@indexMarket")->name("index");
                Route::get("/create", "DigisolServicesController@CreateMarket")->name("create");
                Route::post("/", "DigisolServicesController@storeMarket")->name("store");
                Route::get("/{id}", "DigisolServicesController@editMarket")->name("edit");
                Route::post("/{id}", "DigisolServicesController@UpdateMarket")->name("update");
                Route::delete("/{id}", "DigisolServicesController@destroyMarket")->name("destroy");
            });
        });
    });
        Route::prefix("category_apps")->name("category_apps.")->group(function (){
            Route::get("/all/{page?}","CategoryAppsController@index")->name("index");
            Route::get("/create","CategoryAppsController@create")->name("create");
            Route::post("/","CategoryAppsController@store")->name("store");
            Route::get("/edit/{id}","CategoryAppsController@edit")->name("edit");
            Route::put("/{id}","CategoryAppsController@update")->name("update");
            Route::delete("/{id}","CategoryAppsController@destroy")->name("destroy");
            Route::post("/sort","CategoryAppsController@sort")->name("sort");
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
    #################### // How kadyTech Route ########################################
Route::prefix("KadyTech")->name("KadyTech.")->group(function (){
    Route::get("/", "KadyTechController@index")->name("index");
        // How home Route
        Route::prefix("/home")->name("home.")->group(function (){
            Route::get("/", "KadyTechHomeController@index")->name("index");
            //OurStory Route
            Route::prefix("/Our-Story")->name("OurStory.")->group(function (){
                Route::get("/", "KadyTechHomeController@indexOurStory")->name("index");
                Route::get("/create", "KadyTechHomeController@Create")->name("create");
                Route::post("/", "KadyTechHomeController@Store")->name("store");
                Route::get("/{id}", "KadyTechHomeController@Edit")->name("edit");
                Route::post("/{id}", "KadyTechHomeController@Update")->name("update");
                Route::delete("/{id}", "KadyTechHomeController@Destroy")->name("destroy");
            });
        });
        // kadyTech Social Media Route
        Route::prefix("social")->name("social.")->group(function (){
            Route::get("/", "KadyTechSocialController@index")->name("index");
            Route::get("/create", "KadyTechSocialController@create")->name("create");
            Route::post("/", "KadyTechSocialController@store")->name("store");
            Route::get("/{id}","KadyTechSocialController@edit")->name("edit");
            Route::post("/{id}", "KadyTechSocialController@update")->name("update");
            Route::delete("/{id}", "KadyTechSocialController@destroy")->name("destroy");
        });
        // kadyTech Contact Route
        Route::prefix("contact")->name("contact.")->group(function (){
            Route::get("/","KadyTechContactController@index")->name("index");
            Route::get("/create", "KadyTechContactController@create")->name("create");
            Route::post("/","KadyTechContactController@store")->name("store");
            Route::get("/{id}","KadyTechContactController@edit")->name("edit");
            Route::put("/{id}","KadyTechContactController@update")->name("update");
            Route::delete("/{id}","KadyTechContactController@destroy")->name("destroy");
        });
        // kadyTech Settings
        Route::prefix("/settings")->name("settings.")->group(function (){
            Route::get("/", "KadyTechSettingsController@index")->name("index");
            Route::post("/save", "KadyTechSettingsController@save")->name("save");
        });
    });
Route::get("/email/verification", "ManagerController@VerificationEmail")->name("VerificationEmail");
Route::get("/SendEmail/{id}", "ManagerController@SendEmail")->name("SendEmail");
});

