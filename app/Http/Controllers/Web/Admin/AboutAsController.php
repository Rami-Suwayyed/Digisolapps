<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Helpers\Dialog\Web\Types\WarningMessage;
use App\Http\Controllers\Controller;
use App\Models\AboutAs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AboutAsController extends Controller
{

    public function rules(){
        return [
            "about_ar" => ["required"],
            "about_en" => ['required'],
        ];
    }

    public function colums(){
        return [
          "about_ar" => "Arabic About As",
          "about_en" => "English About As"
        ];
    }

    public function index()
    {
        $data['abouts'] = AboutAs::all();
        return view("admin.about_as.index", $data);
    }


    public function create()
    {
        $findAbout = AboutAs::all();
        if(!$findAbout->isEmpty()){
            $message = (new WarningMessage())->title("Cannot")
                ->body("Cannot be added again");
            Dialog::flashing($message);
            return redirect()->route("admin.about.index");
        }
        return view("admin.about_as.create");
    }


    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), $this->rules(), $this->colums());
        if($valid->fails()){
            return redirect()->route("admin.about.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $abouts = new AboutAs();
        $abouts->about_en = $request->about_en;
        $abouts->about_ar = $request->about_ar;
        if($abouts->save()){
            $abouts->saveMedia($request->file("about_image"));
        }

        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The About As Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.about.index");
    }


    public function edit(Request $request)
    {
        $data['about'] = AboutAs::find($request->id);
        return view("admin.about_as.edit", $data);
    }


    public function update(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rules(), $this->colums());
        if($valid->fails()){
            return redirect()->route("admin.about.edit", ["id" => $request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $about = AboutAs::find($request->id);
        $about->about_en = $request->about_en;
        $about->about_ar = $request->about_ar;
        if($about->save()){
            if($request->hasFile("about_image")) {
                $about->removeAllFiles();
                $about->saveMedia($request->file("about_image"));
            }
        }
        $message = (new SuccessMessage())->title("Update Successfully")
            ->body("The About As Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.about.index");
    }

}
