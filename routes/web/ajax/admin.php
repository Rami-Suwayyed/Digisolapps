<?php

use Illuminate\Support\Facades\Route;

Route::prefix("subjects")->group(function(){
    Route::prefix("general-questions")->name("subject_general_question.")->group(function(){
        Route::prefix("{question_id}/forms")->name("form.")->group(function(){
            Route::post("/", "GeneralSubjectQuestionFormController@save")->name("save");
        });
    });
    Route::name("subject.")->group(function (){
        Route::prefix("{subject_id}/quantities")->name("quantity.")->group(function(){
            Route::post("/{id}/delete", "SubjectController@deleteQuantity")->name("delete");
        });
        Route::prefix("/{subject_id}/properties")->name("property.")->group(function (){
            Route::post("/", "SubjectPropertyController@store")->name("store");
            Route::post("/{property_id}/delete-option", "SubjectPropertyController@deleteOption")->name("delete_option");
        });
        Route::post("/change-status", "SubjectController@changeStatus")->name("change_status");
    });

});

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

Route::prefix("/teachers")->group(function(){
    Route::get("/all/{type?}", "TeacherController@index");
    Route::post("/types/update", "TeacherController@changeType");
});
Route::prefix("/managers")->group(function(){
    Route::get("/all/change", "ManagerController@index");
    Route::post("/change/update", "ManagerController@changeType");
    Route::post("/change-status", "ManagerController@changeStatus")->name("manager.change_status");

});


Route::prefix("/locations-support")->name("locations_support.")->group(function (){
    Route::post("/check", "LocationSupportController@checkingLocation")->name("index");
    Route::post("/cancel", "LocationSupportController@cancel")->name("cancel");
});

//Commissions
Route::prefix("commissions")->name("commissions.")->group(function (){
    Route::post("/", "CommissionController@save")->name("save");
    Route::post("/destroy", "CommissionController@destroy")->name("destroy");
});

Route::prefix("commissions-teacher")->name("commissions-teacher.")->group(function (){
    Route::post("/destroy", "CommissionTeacherController@destroy")->name("destroy");
});

Route::prefix("orders")->name("orders.")->group(function (){
    Route::post("{id}/update/{type}", "OrderController@update")->name("update");
});

// User Routes
Route::prefix("/users")->group(function (){
    Route::post("/change-status", "UserController@changeStatus")->name("user.change_status");
});



Route::prefix("promocode")->name("promocode.")->group(function (){
    Route::get("/get-user", "PromoCodeController@getUser")->name("get_user");
});

Route::prefix("sub-category")->name("sub-category.")->group(function (){
    Route::get("/show", "ShowController@showSub")->name("show");
    Route::get("/", "ShowController@showSubject")->name("subject");
});


Route::prefix("/wallets")->name("wallets.")->group(function () {
    Route::get("/Get/User", "WalletController@GetWalletUser")->name("GetUser");
});

Route::prefix("subjects")->name("subjects.")->group(function(){
    Route::get("/{sub_id}", "SubCategoryController@show")->name("show");
    Route::post("/change-status", "SubjectController@changeStatus")->name("change_status");
});
Route::prefix("main")->name("categories.")->group(function(){
    Route::post("/change-status", "SubjectController@StatusCategories")->name("change_status");
});
Route::prefix("Subcategories")->name("Subcategories.")->group(function(){
    Route::post("/change-status", "SubCategoryController@changeStatus")->name("change_status");
});



Route::prefix("dashboard")->name("dashboard.")->group(function (){
    Route::get("/","DashboardController@index")->name("index");
});

Route::get("/Reports/change", "ReportController@change")->name("Report.change");

