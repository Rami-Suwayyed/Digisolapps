<?php
namespace App\Http\Controllers\Web\Admin;
use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Helpers\Dialog\Web\Types\WarningMessage;
use App\Http\Controllers\Controller;
use App\Models\HomePage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KadyTechHomeController extends Controller
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
        return view("admin.KadyTech.home.index");
    }


    public function indexOurStory()
    {
        $data['homeTitles'] = HomePage::where('type',1)->where('company','kadytech')->get();
        return view("admin.KadyTech.home.OurStory.index", $data);
    }

    public function Create()
    {
        $homeTitles = HomePage::where('type',1)->where('company','kadytech')->get();
        if(!$homeTitles->isEmpty()){
            $message = (new WarningMessage())->title("Cannot")
                ->body("Cannot be added Website Titles");
            Dialog::flashing($message);
            return redirect()->route("admin.KadyTech.home.OurStory.index");
        }
        return view("admin.KadyTech.home.OurStory.create");
    }


    public function Store(Request $request)
    {
        $rules = $this->rules();
        $valid = Validator::make($request->all(), $rules);
        if($valid->fails()){
            return redirect()->route("admin.KadyTech.home.OurStory.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $HomeTitle = new HomePage();
        $HomeTitle->title_ar = $request->title_ar;
        $HomeTitle->title_en = $request->title_en;
        $HomeTitle->description_en = $request->description_en;
        $HomeTitle->description_ar = $request->description_ar;
        $HomeTitle->type =1;
        $HomeTitle->status =1;
        $HomeTitle->company ='kadytech';
        $HomeTitle->save();
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Website Title Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.KadyTech.home.OurStory.index");
    }

    public function Edit(Request $request)
    {
        $data['homeTitle'] = HomePage::findOrFail($request->id);
        return view("admin.KadyTech.home.OurStory.edit", $data);
    }


    public function Update(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.KadyTech.home.OurStory.edit", ["id" => $request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $HomeTitle = HomePage::find($request->id);
        $HomeTitle->title_ar = $request->title_ar;
        $HomeTitle->title_en = $request->title_en;
        $HomeTitle->description_ar = $request->description_ar;
        $HomeTitle->description_en = $request->description_en;
        $HomeTitle->type =1;
        $HomeTitle->status =1;
        $HomeTitle->save();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Website Title Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.KadyTech.home.OurStory.index");
    }


    public function Destroy(Request $request)
    {
        $HomeTitle = HomePage::find($request->id);
        $HomeTitle->delete();
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Website Title Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.KadyTech.home.OurStory.index");
    }


}



