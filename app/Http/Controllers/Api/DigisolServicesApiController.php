<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\ServicePage;

class DigisolServicesApiController extends Controller
{

    public function index(){
        $Services = ServicePage::where('company','digisol')->get();
        foreach ($Services as $index=> $Service){
            $title=$Service->getTitleAttribute();
            $Description=$Service->getDescriptionAttribute();
            switch($Service->type){
                case 1:
                    $data["Mobile"][]=[
                        "id"=>$Service->id,
                        "title"=>$title,
                        "Description"=>$Description];
                    break;
                case 2:
                    $data["Website"][]=[
                        "id"=>$Service->id,
                        "title"=>$title,
                        "Description"=>$Description];
                    break;
                case 3:
                    $data["MARKETING"][]=[
                        "id"=>$Service->id,
                        "title"=>$title,
                        "Description"=>$Description];
                    break;
            }
        }
        return JsonResponse::data($data)->send();
    }
}
