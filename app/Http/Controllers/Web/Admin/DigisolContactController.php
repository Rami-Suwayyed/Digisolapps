<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Helpers\Dialog\Web\Types\WarningMessage;
use App\Http\Controllers\Controller;
use App\Models\DigisolContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DigisolContactController extends Controller
{
    public function rules(){
        return [
            "text_en" => ["required"],
            "text_ar" => ["required"],
            "title_en" => ["required"],
            "title_ar" => ["required"],
        ];
    }

    public function index(Request $request){

        $data["contacts"] = DigisolContact::all();

        return view  ("admin.digisol.contact.index",$data);

    }

    public function destroy(Request $request){
        $description = DigisolContact::findOrFail($request->id);
        $description->delete();
        $message = (new DangerMessage())->title("Delete Successfully")
            ->body("The Contact Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.digisol.contact.index");

    }



}
