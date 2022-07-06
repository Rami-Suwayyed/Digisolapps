<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\DigisolAboutResource;
use App\Models\DigisolAboutUs;

class DigisolAboutApiController extends Controller
{

    public function index(){
        $AboutUs = DigisolAboutUs::all();
        foreach ($AboutUs as $about){
            $title=$about->getTitleAttribute();
            $Description=$about->getDescriptionAttribute();
            switch($about->type){
                case 1:
                    $data["First"]=[
                        "id"=>$about->id,
                        "title"=>$title,
                        "Description"=>$Description];
                    break;
                case 2:
                    $data["Second"]=[
                        "id"=>$about->id,
                        "title"=>$title,
                        "Description"=>$Description];
                    break;
                case 3:
                    $data["Third"]=[
                        "id"=>$about->id,
                        "title"=>$title,
                        "Description"=>$Description];
                    break;
                case 4:
                    $data["Fourth"][]=[
                        "id"=>$about->id,
                        "title"=>$title,
                        "Description"=>$Description];
                    break;
            }
        }
//        $data["About"] = DigisolAboutResource::collection($AboutUs);

        return JsonResponse::data($data)->send();
    }
}
