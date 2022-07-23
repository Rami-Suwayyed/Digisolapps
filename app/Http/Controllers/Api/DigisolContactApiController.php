<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\DigisolContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DigisolContactApiController extends Controller
{

    public function rules(){
        return[
            "name"        => ["required"],
            "email"       => ["required" ,"email"],
            "phone"       => ["required"],
            "description" => ["required"]
        ];
    }

    public function Contact(Request $request){

        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails())
            return JsonResponse::validationErrors($valid->errors()->messages())->send();
            $this->store($request);
            return JsonResponse::success()->send();

    }

    private function store(Request $request)
    {
        $ContactUs = new DigisolContact();
        $ContactUs->name        = $request->name;
        $ContactUs->email       = $request->email;
        $ContactUs->phone       = $request->phone;
        $ContactUs->description = $request->description;
        $ContactUs->save();
    }
}
