<?php
namespace App\Http\Controllers\Web\Admin;
use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Helpers\Dialog\Web\Types\WarningMessage;
use App\Http\Controllers\Controller;
use App\Models\DigisolAboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DigisolAboutController extends Controller
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
        return view("admin.digisol.about.index");
    }


    public function indexFirst()
    {
        $data['firsts'] = DigisolAboutUs::where('type',1)->get();
        return view("admin.digisol.about.first.index", $data);
    }

    public function CreateFirst()
    {
        $Firsts = DigisolAboutUs::where('type',1)->get();
        if(!$Firsts->isEmpty()){
            $message = (new WarningMessage())->title("Cannot")
                ->body("Cannot be added Website first Paragraph");
            Dialog::flashing($message);
            return redirect()->route("admin.digisol.about.first.index");
        }
        return view("admin.digisol.about.first.create");
    }


    public function storeFirst(Request $request)
    {
        $rules = $this->rules();
        $valid = Validator::make($request->all(), $rules);
        if($valid->fails()){
            return redirect()->route("admin.digisol.about.first.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $FirstP = new DigisolAboutUs();
        $FirstP->title_ar = $request->title_ar;
        $FirstP->title_en = $request->title_en;
        $FirstP->description_en = $request->description_en;
        $FirstP->description_ar = $request->description_ar;
        $FirstP->type = 1;
        $FirstP->save();
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Website first Paragraph Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.about.first.index");
    }

    public function editFirst(Request $request)
    {
        $data['First'] = DigisolAboutUs::findOrFail($request->id);
        return view("admin.digisol.about.first.edit", $data);
    }


    public function updateFirst(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.digisol.about.first.edit", ["id" => $request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $FirstP = DigisolAboutUs::find($request->id);
        $FirstP->title_ar = $request->title_ar;
        $FirstP->title_en = $request->title_en;
        $FirstP->description_en = $request->description_en;
        $FirstP->description_ar = $request->description_ar;
        $FirstP->type = 1;
        $FirstP->save();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Website first Paragraph Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.about.first.index");
    }


    public function destroyFirst(Request $request)
    {
        $FirstP = DigisolAboutUs::find($request->id);
        $FirstP->delete();
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Website first Paragraph Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.about.first.index");
    }

    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< SecondParagraph >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

    public function indexSecond()
    {
        $data['Seconds'] = DigisolAboutUs::where('type',2)->get();
        return view("admin.digisol.about.second.index", $data);
    }

    public function CreateSecond()
    {
        $Paragraph = DigisolAboutUs::where('type',2)->get();
        if(!$Paragraph->isEmpty()){
            $message = (new WarningMessage())->title("Cannot")
                ->body("Cannot be added Website Second Paragraph");
            Dialog::flashing($message);
            return redirect()->route("admin.digisol.about.second.index");
        }
        return view("admin.digisol.about.second.create");
    }


    public function storeSecond(Request $request)
    {
        $rules = $this->rules();
        $valid = Validator::make($request->all(), $rules);
        if($valid->fails()){
            return redirect()->route("admin.digisol.about.second.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $Paragraph = new DigisolAboutUs();
        $Paragraph->title_ar = $request->title_ar;
        $Paragraph->title_en = $request->title_en;
        $Paragraph->description_en = $request->description_en;
        $Paragraph->description_ar = $request->description_ar;
        $Paragraph->type = 2;
        $Paragraph->save();
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Website SecondParagraph Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.about.second.index");
    }

    public function editSecond(Request $request)
    {
        $data['Second'] = DigisolAboutUs::findOrFail($request->id);
        return view("admin.digisol.about.second.edit", $data);
    }


    public function updateSecond(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.digisol.about.second.edit", ["id" => $request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $Paragraph = DigisolAboutUs::find($request->id);
        $Paragraph->title_ar = $request->title_ar;
        $Paragraph->title_en = $request->title_en;
        $Paragraph->description_ar = $request->description_ar;
        $Paragraph->description_en = $request->description_en;
        $Paragraph->type = 2;
        $Paragraph->save();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Website Second Paragraph Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.about.second.index");
    }


    public function destroySecond(Request $request)
    {
        $Paragraph = DigisolAboutUs::find($request->id);
        $Paragraph->delete();
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Website Second Paragraph Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.about.second.index");
    }



    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< indexThird >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

    public function indexThird()
    {
        $data['thirds'] = DigisolAboutUs::where('type',3)->get();
        return view("admin.digisol.about.third.index", $data);
    }

    public function CreateThird()
    {
        $Paragraph = DigisolAboutUs::where('type',3)->get();
        if(!$Paragraph->isEmpty()){
            $message = (new WarningMessage())->title("Cannot")
                ->body("Cannot be added Website third Paragraph");
            Dialog::flashing($message);
            return redirect()->route("admin.digisol.about.third.index");
        }
        return view("admin.digisol.about.third.create");
    }


    public function storeThird(Request $request)
    {
        $rules = $this->rules();
        $valid = Validator::make($request->all(), $rules);
        if($valid->fails()){
            return redirect()->route("admin.digisol.about.third.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $Paragraph = new DigisolAboutUs();
        $Paragraph->title_ar = $request->title_ar;
        $Paragraph->title_en = $request->title_en;
        $Paragraph->description_en = $request->description_en;
        $Paragraph->description_ar = $request->description_ar;
        $Paragraph->type = 3;
        $Paragraph->save();
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Website SecondParagraph Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.about.third.index");
    }

    public function editThird(Request $request)
    {
        $data['third'] = DigisolAboutUs::findOrFail($request->id);
        return view("admin.digisol.about.third.edit", $data);
    }


    public function updateThird(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.digisol.about.third.edit", ["id" => $request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $Paragraph = DigisolAboutUs::find($request->id);
        $Paragraph->title_ar = $request->title_ar;
        $Paragraph->title_en = $request->title_en;
        $Paragraph->description_ar = $request->description_ar;
        $Paragraph->description_en = $request->description_en;
        $Paragraph->type = 3;
        $Paragraph->save();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Website Second Paragraph Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.about.third.index");
    }


    public function destroyThird(Request $request)
    {
        $Paragraph = DigisolAboutUs::find($request->id);
        $Paragraph->delete();
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Website Second Paragraph Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.about.third.index");
    }


    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< indexThird >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

    public function indexFourth()
    {
        $data['fourths'] = DigisolAboutUs::where('type',4)->get();
        return view("admin.digisol.about.fourth.index", $data);
    }

    public function CreateFourth()
    {
        return view("admin.digisol.about.fourth.create");
    }


    public function storeFourth(Request $request)
    {
        $rules = $this->rules();
        $valid = Validator::make($request->all(), $rules);
        if($valid->fails()){
            return redirect()->route("admin.digisol.about.fourth.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $Paragraph = new DigisolAboutUs();
        $Paragraph->title_ar = $request->title_ar;
        $Paragraph->title_en = $request->title_en;
        $Paragraph->description_en = $request->description_en;
        $Paragraph->description_ar = $request->description_ar;
        $Paragraph->type = 4;
        $Paragraph->save();
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Website fourth Paragraph Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.about.fourth.index");
    }

    public function editFourth(Request $request)
    {
        $data['fourth'] = DigisolAboutUs::findOrFail($request->id);
        return view("admin.digisol.about.fourth.edit", $data);
    }


    public function updateFourth(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.digisol.about.fourth.edit", ["id" => $request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $Paragraph = DigisolAboutUs::find($request->id);
        $Paragraph->title_ar = $request->title_ar;
        $Paragraph->title_en = $request->title_en;
        $Paragraph->description_ar = $request->description_ar;
        $Paragraph->description_en = $request->description_en;
        $Paragraph->type = 4;
        $Paragraph->save();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Website fourth Paragraph Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.about.fourth.index");
    }


    public function destroyFourth(Request $request)
    {
        $Paragraph = DigisolAboutUs::find($request->id);
        $Paragraph->delete();
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Website fourth Paragraph Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.about.fourth.index");
    }


}



