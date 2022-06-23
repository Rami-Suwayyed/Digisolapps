<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\InstructionsAndLaws;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InstructionsAndLawsController extends Controller
{

    public function rules(){
        return [
            "name_en" => ["required"],
            "name_ar" => ['required']
        ];
    }

    public function colums(){
        return [
            "name_en" => "English Instructions And Laws",
            "name_ar" => "Arabic Instructions And Laws"
        ];
    }

    public function index()
    {
        $data['instructions'] = InstructionsAndLaws::all();
        return view("admin.instructions_and_laws.index", $data);
    }


    public function create()
    {
        return view("admin.instructions_and_laws.create");
    }


    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), $this->rules(), $this->colums());
        if($valid->fails()){
            return redirect()->route("admin.instructions.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $Instructions = new InstructionsAndLaws();
        $Instructions->name_en = $request->name_en;
        $Instructions->name_ar = $request->name_ar;
        $Instructions->save();
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Instructions And Laws Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.instructions.index");
    }


    public function edit(Request $request)
    {
        $data['instruction'] = InstructionsAndLaws::findOrFail($request->id);
        return view("admin.instructions_and_laws.edit", $data);
    }

    public function update(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rules(), $this->colums());
        if($valid->fails()){
            return redirect()->route("admin.instructions.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $Instructions = InstructionsAndLaws::findOrFail($request->id);
        $Instructions->name_ar = $request->name_ar;
        $Instructions->name_en = $request->name_en;
        $Instructions->save();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Instructions And Laws Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.instructions.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $Instructions = InstructionsAndLaws::findOrFail($request->id);
        $Instructions->delete();
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Instructions And Laws Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.instructions.index");
    }
}
