<?php
namespace App\Http\Controllers\Web\Admin;
use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Helpers\Dialog\Web\Types\WarningMessage;
use App\Http\Controllers\Controller;
use App\Models\DigisolSecondParagraph;
use App\Models\HomeTestimonial;
use App\Models\HomeTitle;
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
        $data['homeTitles'] = HomeTitle::all();
        return view("admin.digisol.home.title.index", $data);
    }

    public function CreateTitle()
    {
        $homeTitles = HomeTitle::all();
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
        $HomeTitle = new HomeTitle();
        $HomeTitle->title_ar = $request->title_ar;
        $HomeTitle->title_en = $request->title_en;
        $HomeTitle->description_en = $request->description_en;
        $HomeTitle->description_ar = $request->description_ar;
        $HomeTitle->save();
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Website Title Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.home.title.index");
    }

    public function editTitle(Request $request)
    {
        $data['homeTitle'] = HomeTitle::findOrFail($request->id);
        return view("admin.digisol.home.title.edit", $data);
    }


    public function updateTitle(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.digisol.home.title.edit", ["id" => $request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $HomeTitle = HomeTitle::find($request->id);
        $HomeTitle->title_ar = $request->title_ar;
        $HomeTitle->title_en = $request->title_en;
        $HomeTitle->description_ar = $request->description_ar;
        $HomeTitle->description_en = $request->description_en;
        $HomeTitle->save();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Website Title Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.home.title.index");
    }


    public function destroyTitle(Request $request)
    {
        $HomeTitle = HomeTitle::find($request->id);
        $HomeTitle->delete();
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Website Title Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.home.title.index");
    }



    public function indexSecondParagraph()
    {
        $data['Paragraphs'] = DigisolSecondParagraph::all();
        return view("admin.digisol.home.SecondParagraph.index", $data);
    }

    public function CreateSecondParagraph()
    {
        $Paragraph = DigisolSecondParagraph::all();
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
        $Paragraph = new DigisolSecondParagraph();
        $Paragraph->title_ar = $request->title_ar;
        $Paragraph->title_en = $request->title_en;
        $Paragraph->description_en = $request->description_en;
        $Paragraph->description_ar = $request->description_ar;
        $Paragraph->save();
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Website SecondParagraph Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.home.SecondParagraph.index");
    }

    public function editSecondParagraph(Request $request)
    {
        $data['Paragraph'] = DigisolSecondParagraph::findOrFail($request->id);
        return view("admin.digisol.home.SecondParagraph.edit", $data);
    }


    public function updateSecondParagraph(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.digisol.home.SecondParagraph.edit", ["id" => $request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $Paragraph = DigisolSecondParagraph::find($request->id);
        $Paragraph->title_ar = $request->title_ar;
        $Paragraph->title_en = $request->title_en;
        $Paragraph->description_ar = $request->description_ar;
        $Paragraph->description_en = $request->description_en;
        $Paragraph->save();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Website Second Paragraph Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.home.SecondParagraph.index");
    }


    public function destroySecondParagraph(Request $request)
    {
        $Paragraph = DigisolSecondParagraph::find($request->id);
        $Paragraph->delete();
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Website Second Paragraph Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.home.SecondParagraph.index");
    }






    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Testimonials >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>


    public function indexBody()
    {
        $data['testimonials'] = HomeTestimonial::all();
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
        $HomeTitle = new HomeTestimonial();
        $HomeTitle->name = $request->name;
        $HomeTitle->body = $request->body;
        $HomeTitle->date = $request->date;
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
        $data['homeTitle'] = HomeTestimonial::findOrFail($request->id);
        return view("admin.digisol.home.body.edit", $data);
    }


    public function updateBody(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.digisol.home.title.edit", ["id" => $request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $HomeTitle = HomeTestimonial::find($request->id);
        $HomeTitle->title_ar = $request->title_ar;
        $HomeTitle->title_en = $request->title_en;
        $HomeTitle->description_ar = $request->description_ar;
        $HomeTitle->description_en = $request->description_en;
        $HomeTitle->save();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Website Title Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.home.title.index");
    }


    public function destroyBody(Request $request)
    {
        $HomeTitle = HomeTestimonial::find($request->id);
        $HomeTitle->delete();
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Website Title Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.home.title.index");
    }

}



