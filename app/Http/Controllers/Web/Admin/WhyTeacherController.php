<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\HowToOrder;
use App\Models\WhyTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WhyTeacherController extends Controller
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
        $data["WhyTeacher"] = WhyTeacher::all();
        return view("admin.why_Teacher.index", $data);
    }

    public function create()
    {
        return view("admin.why_Teacher.create");
    }

    public function store(Request $request)
    {
        $rules = $this->rules();
        $rules['image'] = ["required"];
        $valid = Validator::make($request->all(), $rules);
        if($valid->fails()){
            return redirect()->route("admin.digisol.why-Teacher.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $whyTeacher = new WhyTeacher();
        $whyTeacher->title_ar = $request->title_ar;
        $whyTeacher->title_en = $request->title_en;
        $whyTeacher->description_en = $request->description_en;
        $whyTeacher->description_ar = $request->description_ar;
        if($whyTeacher->save()){
            if($request->hasFile("image")){
                $whyTeacher->saveMedia($request->file("image"));
            }
        }
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Why Teacher Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.why-Teacher.index");
    }


    public function edit(Request $request)
    {
        $data['WhyTeacher'] = WhyTeacher::findOrFail($request->id);
        return view("admin.why_Teacher.edit", $data);
    }


    public function update(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.digisol.why-Teacher.edit", ["id" => $request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $whyTeacher = WhyTeacher::find($request->id);
        $whyTeacher->title_ar = $request->title_ar;
        $whyTeacher->title_en = $request->title_en;
        $whyTeacher->description_ar = $request->description_ar;
        $whyTeacher->description_en = $request->description_en;
        if($whyTeacher->save()){
            if($request->hasFile("image")) {
                $whyTeacher->removeAllFiles();
                $whyTeacher->saveMedia($request->file("image"));
            }
        }
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Why Teacher Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.why-Teacher.index");
    }


    public function destroy(Request $request)
    {
        $whyTeacher = WhyTeacher::find($request->id);
        if($whyTeacher->delete()){
            $whyTeacher->removeAllFiles();
        }
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The How To Orders Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.why-Teacher.index");
    }
}
