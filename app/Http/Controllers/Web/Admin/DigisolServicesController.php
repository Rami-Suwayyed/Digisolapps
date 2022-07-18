<?php
namespace App\Http\Controllers\Web\Admin;
use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Helpers\Dialog\Web\Types\WarningMessage;
use App\Http\Controllers\Controller;
use App\Models\DigisolService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DigisolServicesController extends Controller
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
        return view("admin.digisol.services.index");
    }


    public function indexMobile()
    {
        $data['mobiles'] = DigisolService::where('type',1)->get();
        return view("admin.digisol.services.mobile.index", $data);
    }

    public function CreateMobile()
    {
        $mobiles= DigisolService::where('type',1)->get();
        if(!$mobiles->isEmpty()){
            $message = (new WarningMessage())->title("Cannot")
                ->body("Cannot be added Website services mobile");
            Dialog::flashing($message);
            return redirect()->route("admin.digisol.Services.mobile.index");
        }
        return view("admin.digisol.services.mobile.create");
    }


    public function StoreMobile(Request $request)
    {
        $rules = $this->rules();
        $valid = Validator::make($request->all(), $rules);
        if($valid->fails()){
            return redirect()->route("admin.digisol.Services.mobile.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $mobile = new DigisolService();
        $mobile->title_ar = $request->title_ar;
        $mobile->title_en = $request->title_en;
        $mobile->description_en = $request->description_en;
        $mobile->description_ar = $request->description_ar;
        $mobile->type = 1;
        $mobile->save();
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Website services mobile Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.Services.mobile.index");
    }

    public function EditMobile(Request $request)
    {
        $data['mobile'] = DigisolService::findOrFail($request->id);
        return view("admin.digisol.services.mobile.edit", $data);
    }


    public function UpdateMobile(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.digisol.Services.mobile.edit", ["id" => $request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $mobile = DigisolService::find($request->id);
        $mobile->title_ar = $request->title_ar;
        $mobile->title_en = $request->title_en;
        $mobile->description_ar = $request->description_ar;
        $mobile->type = 1;
        $mobile->save();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Website services mobile Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.Services.mobile.index");
    }


    public function DestroyMobile(Request $request)
    {
        $mobile = DigisolService::find($request->id);
        $mobile->delete();
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Website services mobile Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.Services.mobile.index");
    }

    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< WebParagraph >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

    public function indexWeb()
    {
        $data['webs'] = DigisolService::where('type',2)->get();
        return view("admin.digisol.services.website.index", $data);
    }

    public function CreateWeb()
    {
        $webs = DigisolService::where('type',2)->get();
        if(!$webs->isEmpty()){
            $message = (new WarningMessage())->title("Cannot")
                ->body("Cannot be added Website Web  Development");
            Dialog::flashing($message);
            return redirect()->route("admin.digisol.Services.Web.index");
        }
        return view("admin.digisol.services.website.create");
    }


    public function storeWeb(Request $request)
    {
        $rules = $this->rules();
        $valid = Validator::make($request->all(), $rules);
        if($valid->fails()){
            return redirect()->route("admin.digisol.Services.Web.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $web = new DigisolService();
        $web->title_ar = $request->title_ar;
        $web->title_en = $request->title_en;
        $web->description_en = $request->description_en;
        $web->description_ar = $request->description_ar;
        $web->type = 2;
        $web->save();
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Website WebParagraph Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.Services.Web.index");
    }

    public function editWeb(Request $request)
    {
        $data['web'] = DigisolService::findOrFail($request->id);
        return view("admin.digisol.services.website.edit", $data);
    }


    public function UpdateWeb(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.digisol.Services.Web.edit", ["id" => $request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $web = DigisolService::find($request->id);
        $web->title_ar = $request->title_ar;
        $web->title_en = $request->title_en;
        $web->description_ar = $request->description_ar;
        $web->description_en = $request->description_en;
        $web->type = 2;
        $web->save();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Website Web Paragraph Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.Services.Web.index");
    }


    public function destroyWeb(Request $request)
    {
        $web = DigisolService::find($request->id);
        $web->delete();
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Website Web Paragraph Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.Services.Web.index");
    }



    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< indexMarket >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

    public function indexMarket()
    {
        $data['markets'] = DigisolService::where('type',3)->get();
        return view("admin.digisol.services.marketing.index", $data);
    }

    public function CreateMarket()
    {
        $marketing = DigisolService::where('type',3)->get();
        if(!$marketing->isEmpty()){
            $message = (new WarningMessage())->title("Cannot")
                ->body("Cannot be added Website marketing Paragraph");
            Dialog::flashing($message);
            return redirect()->route("admin.digisol.Services.market.index");
        }
        return view("admin.digisol.services.marketing.create");
    }


    public function storeMarket(Request $request)
    {
        $rules = $this->rules();
        $valid = Validator::make($request->all(), $rules);
        if($valid->fails()){
            return redirect()->route("admin.digisol.Services.market.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $market = new DigisolService();
        $market->title_ar = $request->title_ar;
        $market->title_en = $request->title_en;
        $market->description_en = $request->description_en;
        $market->description_ar = $request->description_ar;
        $market->type = 3;
        $market->save();
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Website WebParagraph Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.Services.market.index");
    }

    public function editMarket(Request $request)
    {
        $data['market'] = DigisolService::findOrFail($request->id);
        return view("admin.digisol.services.marketing.edit", $data);
    }


    public function UpdateMarket(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.digisol.Services.market.edit", ["id" => $request->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $market = DigisolService::find($request->id);
        $market->title_ar = $request->title_ar;
        $market->title_en = $request->title_en;
        $market->description_ar = $request->description_ar;
        $market->description_en = $request->description_en;
        $market->type = 3;
        $market->save();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Website Web Paragraph Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.Services.market.index");
    }


    public function destroyMarket(Request $request)
    {
        $market = DigisolService::find($request->id);
        $market->delete();
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Website Web Paragraph Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.Services.market.index");
    }


    // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< indexMarket >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
}



