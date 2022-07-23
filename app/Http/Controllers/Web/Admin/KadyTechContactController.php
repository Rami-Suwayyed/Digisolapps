<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Http\Controllers\Controller;
use App\Models\KadyTechContact;
use Illuminate\Http\Request;


class KadyTechContactController extends Controller
{


    public function index(Request $request){

        $data["contacts"] = KadyTechContact::all();

        return view  ("admin.KadyTech.contact.index",$data);

    }

    public function destroy(Request $request){
        $description = KadyTechContact::findOrFail($request->id);
        $description->delete();
        $message = (new DangerMessage())->title("Delete Successfully")
            ->body("The Contact Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.KadyTech.contact.index");

    }



}
