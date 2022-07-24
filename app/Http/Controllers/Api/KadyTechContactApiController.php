<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KadyTechContactApiController extends Controller
{

    public function rules(){
        return[
            "full_name"        => ["required"],
            "email"       => ["required" ,"email"],
            "phone_number"       => ["required"],
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
        $ContactUs = new ContactUs();
        $ContactUs->name             = $request->full_name;
        $ContactUs->email            = $request->email;
        $ContactUs->phone            = $request->phone_number;
        $ContactUs->type             = 'kadytech';
        $ContactUs->company_name     = $request->company_name ?? null;
        $ContactUs->description      = $request->WebSite_URL ?? null;
        $ContactUs->social_media     = $request->social_media_account ?? null;
        $ContactUs->web              = $request->web ?? 0;
        $ContactUs->mobile           = $request->mobile ?? 0;
        $ContactUs->market           = $request->market ?? 0;
        $ContactUs->other            = $request->other ?? 0;
        $ContactUs->save();
    }
}
