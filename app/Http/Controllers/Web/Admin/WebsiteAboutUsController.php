<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Helpers\Dialog\Web\Types\WarningMessage;
use App\Http\Controllers\Controller;
use App\Models\SaysAboutUs;
use App\Models\WebsiteTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebsiteAboutUsController extends Controller
{

    public function rules(){
        $rules = [
            "title_ar" => ["required", "max:255"],
            "title_en" => ["required", "max:255"],
            "description_ar" => ["required"],
            "description_en" => ["required"]
        ];
        return $rules;
    }

    public function index()
    {
        $data['SaysAboutUs'] = SaysAboutUs::all();
        return view("admin.digisol.says_about_us.index", $data);
    }


    public function create()
    {
        $saysAbout = SaysAboutUs::all();
        if(!$saysAbout->isEmpty()){
            $message = (new WarningMessage())->title("Cannot")
                ->body("Cannot be added Website says About");
            Dialog::flashing($message);
            return redirect()->route("admin.digisol.about-us.index");
        }
        return view("admin.digisol.says_about_us.create");
    }


    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.digisol.about-us.create", ["id" => $request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $saysAbout = new SaysAboutUs();
        $saysAbout->title_ar = $request->title_ar;
        $saysAbout->title_en = $request->title_en;
        $saysAbout->description_en = $request->description_en;
        $saysAbout->description_ar = $request->description_ar;
        $saysAbout->save();

        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Says About Us Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.about-us.index");
    }

    public function edit(Request $request)
    {
        $data['saysAboutUS'] = SaysAboutUs::find($request->id);
        return view("admin.digisol.says_about_us.edit", $data);
    }


    public function update(Request $request, $id)
    {

        $saysAbout = SaysAboutUs::find($request->id);
        $saysAbout->title_ar = $request->title_ar;
        $saysAbout->title_en = $request->title_en;
        $saysAbout->description_ar = $request->description_ar;
        $saysAbout->description_en = $request->description_en;
        $saysAbout->save();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Says About Us Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.about-us.index");
    }

    public function destroy(Request $request)
    {
        $saysAbout = SaysAboutUs::find($request->id);
        $saysAbout->delete();

        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Says About Us Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.about-us.index");
    }
}
