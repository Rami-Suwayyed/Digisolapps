<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Helpers\Dialog\Web\Types\WarningMessage;
use App\Http\Controllers\Controller;
use App\Models\DigisolApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DigisolAppsController extends Controller
{
    public function rules()
    {
        $rules = [
            "name_en" => ["required", "max:255"],
            "name_ar" => ["required", "max:255"],
            "description_ar" => ["required"],
            "description_en" => ["required"],
            "icon" => ["required"],
            "background" => ["required"],
            "phone" => ["required"],
        ];
        return $rules;
    }

    public function rulesUpdate()
    {
        $rules = [
            "name_en" => ["required", "max:255"],
            "name_ar" => ["required", "max:255"],
            "description_ar" => ["required"],
            "description_en" => ["required"],
        ];
        return $rules;
    }

    public function index()
    {
        $data["apps"] = DigisolApp::all();
        return view("admin.digisol.apps.index", $data);
    }


    public function create()
    {
        return view("admin.digisol.apps.create");
    }


    public function store(Request $request)
    {
        $rules = $this->rules();
        $valid = Validator::make($request->all(), $rules);
        if($valid->fails()){
            return redirect()->route("admin.digisol.apps.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $apps = new DigisolApp();
        $apps->name_en = $request->name_en;
        $apps->name_ar = $request->name_ar;
        $apps->description_en = $request->description_en;
        $apps->description_ar = $request->description_ar;
        if($apps->save()){
            if($request->hasFile("icon")){
                $apps->saveMedia($request->file("icon"), "icon");
            }
            if($request->hasFile("background")){
                $apps->saveMedia($request->file("background"), "background");
            }
            if($request->hasFile("phone")){
                $apps->saveMedia($request->file("phone"), "phone");
            }
        }
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The App Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.apps.index");
    }


    public function edit(Request $request)
    {
        $data['app'] = DigisolApp::findOrFail($request->id);
        return view("admin.digisol.apps.edit", $data);
    }


    public function Show(Request $request)
    {
        $data['app'] = DigisolApp::findOrFail($request->id);
        return view("admin.digisol.apps.show", $data);
    }


    public function update(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rulesUpdate());
        if($valid->fails()){
            return redirect()->route("admin.digisol.apps.edit", ["id" => $request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $app = DigisolApp::find($request->id);
        $app->name_en = $request->name_en;
        $app->name_ar = $request->name_ar;
        $app->description_ar = $request->description_ar;
        $app->description_en = $request->description_en;
        $app->save();
        if($request->file("icon")){
            if($app->getFirstMediaFile("icon"))
                $app->removeMedia($app->getFirstMediaFile("icon"));
            $app->saveMedia($request->file("icon"), "icon");
        }
        if($request->file("background")){
            if($app->getFirstMediaFile("background"))
                $app->removeMedia($app->getFirstMediaFile("background"));
            $app->saveMedia($request->file("background"), "background");
        }
        if($request->hasFile("phone")){
            if($app->getFirstMediaFile("phone"))
                $app->removeMedia($app->getFirstMediaFile("phone"));
            $app->saveMedia($request->file("phone"), "phone");
        }
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The apps Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.apps.index");
    }


    public function destroy(Request $request)
    {
        $apps = DigisolApp::find($request->id);
        $apps->delete();
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The apps Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.apps.index");
    }
}
