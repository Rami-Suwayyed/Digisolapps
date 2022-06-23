<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Helpers\Dialog\Web\Types\WarningMessage;
use App\Http\Controllers\Controller;
use App\Models\WebsiteWeWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DigisolAppsController extends Controller
{
    public function rules()
    {
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
        $data["ourVisions"] = WebsiteWeWork::all();
        return view("admin.digisol.how_we_work.index", $data);
    }


    public function create()
    {
        $WebsiteWeWork = WebsiteWeWork::all();
        if(!$WebsiteWeWork->isEmpty()){
            $message = (new WarningMessage())->title("Cannot")
                ->body("Cannot be added Website We Work");
            Dialog::flashing($message);
            return redirect()->route("admin.digisol.how-we-work.index");
        }
        return view("admin.digisol.how_we_work.create");
    }


    public function store(Request $request)
    {
        $rules = $this->rules();
        $valid = Validator::make($request->all(), $rules);
        if($valid->fails()){
            return redirect()->route("admin.digisol.how-we-work.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $ourVision = new WebsiteWeWork();
        $ourVision->title_ar = $request->title_ar;
        $ourVision->title_en = $request->title_en;
        $ourVision->description_en = $request->description_en;
        $ourVision->description_ar = $request->description_ar;
        $ourVision->save();
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Our Vision Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.how-we-work.index");
    }


    public function edit(Request $request)
    {
        $data['how_we_work'] = WebsiteWeWork::findOrFail($request->id);
        return view("admin.digisol.how_we_work.edit", $data);
    }


    public function update(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.digisol.how-we-work.edit", ["id" => $request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $ourVision = WebsiteWeWork::find($request->id);
        $ourVision->title_ar = $request->title_ar;
        $ourVision->title_en = $request->title_en;
        $ourVision->description_ar = $request->description_ar;
        $ourVision->description_en = $request->description_en;
        $ourVision->save();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Our Vision Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.how-we-work.index");
    }


    public function destroy(Request $request)
    {
        $ourVision = WebsiteWeWork::find($request->id);
        $ourVision->delete();
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Our Vision Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.how-we-work.index");
    }
}
