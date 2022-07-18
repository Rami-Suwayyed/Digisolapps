<?php
namespace App\Http\Controllers\Web\Admin;
use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Helpers\Dialog\Web\Types\WarningMessage;
use App\Http\Controllers\Controller;
use App\Models\DigisolHome;
use App\Models\DigisolSecondParagraph;
use App\Models\HomeTestimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DigisolHomeController extends Controller
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

    public function Bodyrules()
    {
        $rules = [
            "name" => ["required", "max:255"],
            "body" => ["required"],
            "date" => ["required"],
            "image" => ["required"]
        ];
        return $rules;
    }

    public function index()
    {
        return view("admin.digisol.home.index");
    }


    public function indexTitle()
    {
        $data['homeTitles'] = DigisolHome::where('type',1)->get();
        return view("admin.digisol.home.title.index", $data);
    }

    public function CreateTitle()
    {
        $homeTitles = DigisolHome::where('type',1)->get();
        if(!$homeTitles->isEmpty()){
            $message = (new WarningMessage())->title("Cannot")
                ->body("Cannot be added Website Titles");
            Dialog::flashing($message);
            return redirect()->route("admin.digisol.home.title.index");
        }
        return view("admin.digisol.home.title.create");
    }


    public function storeTitle(Request $request)
    {
        $rules = $this->rules();
        $valid = Validator::make($request->all(), $rules);
        if($valid->fails()){
            return redirect()->route("admin.digisol.home.title.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $HomeTitle = new DigisolHome();
        $HomeTitle->title_ar = $request->title_ar;
        $HomeTitle->title_en = $request->title_en;
        $HomeTitle->description_en = $request->description_en;
        $HomeTitle->description_ar = $request->description_ar;
        $HomeTitle->type =1;
        $HomeTitle->status =1;
        $HomeTitle->save();
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Website Title Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.home.title.index");
    }

    public function editTitle(Request $request)
    {
        $data['homeTitle'] = DigisolHome::findOrFail($request->id);
        return view("admin.digisol.home.title.edit", $data);
    }


    public function updateTitle(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.digisol.home.title.edit", ["id" => $request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $HomeTitle = DigisolHome::find($request->id);
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
        return redirect()->route("admin.digisol.home.title.index");
    }


    public function destroyTitle(Request $request)
    {
        $HomeTitle = DigisolHome::find($request->id);
        $HomeTitle->delete();
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Website Title Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.home.title.index");
    }



    public function indexSecondParagraph()
    {
        $data['Paragraphs'] = DigisolHome::where('type',2)->get();
        return view("admin.digisol.home.SecondParagraph.index", $data);
    }

    public function CreateSecondParagraph()
    {
        $Paragraph = DigisolHome::where('type',2)->get();
        if(!$Paragraph->isEmpty()){
            $message = (new WarningMessage())->title("Cannot")
                ->body("Cannot be added Website Second Paragraph");
            Dialog::flashing($message);
            return redirect()->route("admin.digisol.home.SecondParagraph.index");
        }
        return view("admin.digisol.home.SecondParagraph.create");
    }


    public function storeSecondParagraph(Request $request)
    {
        $rules = $this->rules();
        $valid = Validator::make($request->all(), $rules);
        if($valid->fails()){
            return redirect()->route("admin.digisol.home.SecondParagraph.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $Paragraph = new DigisolHome();
        $Paragraph->title_ar = $request->title_ar;
        $Paragraph->title_en = $request->title_en;
        $Paragraph->description_en = $request->description_en;
        $Paragraph->description_ar = $request->description_ar;
        $Paragraph->type =2;
        $Paragraph->status =1;
        $Paragraph->save();
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Website SecondParagraph Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.home.SecondParagraph.index");
    }

    public function editSecondParagraph(Request $request)
    {
        $data['Paragraph'] = DigisolHome::findOrFail($request->id);
        return view("admin.digisol.home.SecondParagraph.edit", $data);
    }


    public function updateSecondParagraph(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.digisol.home.SecondParagraph.edit", ["id" => $request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $Paragraph = DigisolHome::find($request->id);
        $Paragraph->title_ar = $request->title_ar;
        $Paragraph->title_en = $request->title_en;
        $Paragraph->description_ar = $request->description_ar;
        $Paragraph->description_en = $request->description_en;
        $Paragraph->type =2;
        $Paragraph->status =1;
        $Paragraph->save();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Website Second Paragraph Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.home.SecondParagraph.index");
    }


    public function destroySecondParagraph(Request $request)
    {
        $Paragraph = DigisolHome::find($request->id);
        $Paragraph->delete();
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Website Second Paragraph Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.home.SecondParagraph.index");
    }


    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Testimonials >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>


    public function indexBody()
    {
        $data['testimonials'] = DigisolHome::where('type',3)->get();
        return view("admin.digisol.home.testimonials.index", $data);
    }

    public function CreateBody()
    {
        return view("admin.digisol.home.testimonials.create");
    }


    public function storeBody(Request $request)
    {
        $rules = $this->Bodyrules();
        $valid = Validator::make($request->all(), $rules);
        if($valid->fails()){
            return redirect()->route("admin.digisol.home.body.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $HomeTitle = new DigisolHome();
        $HomeTitle->title_ar = $request->name;
        $HomeTitle->title_en = $request->name;
        $HomeTitle->description_ar = $request->body;
        $HomeTitle->description_en = $request->body;
        $HomeTitle->date = $request->date;
        $HomeTitle->type =3;
        $HomeTitle->status =1;
        $HomeTitle->save();
        if($HomeTitle->save()){
            $HomeTitle->saveMedia($request->file("image"));
        }
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Website Our Clients Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.home.body.index");
    }

    public function editBody(Request $request)
    {
        $data['homeTitle'] = DigisolHome::findOrFail($request->id);
        return view("admin.digisol.home.body.edit", $data);
    }


    public function updateBody(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.digisol.home.title.edit", ["id" => $request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $HomeTitle = DigisolHome::find($request->id);
        $HomeTitle->title_ar = $request->title_ar;
        $HomeTitle->title_en = $request->title_en;
        $HomeTitle->description_ar = $request->description_ar;
        $HomeTitle->description_en = $request->description_en;
        $HomeTitle->type =2;
        $HomeTitle->status =1;
        $HomeTitle->save();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Website Title Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.home.title.index");
    }


    public function destroyBody(Request $request)
    {
        $HomeTitle = DigisolHome::find($request->id);
        $HomeTitle->delete();
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Website Title Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.home.title.index");
    }

}



