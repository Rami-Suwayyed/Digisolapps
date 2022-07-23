<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Helpers\Media\Src\MediaDefaultPhotos;
use App\Http\Controllers\Controller;
use App\Http\Resources\DigisolHomeResource;
use App\Models\DigisolApp;
use App\Models\DigisolHome;
use App\Models\HomeTestimonial;


class DigisolHomeApiController extends Controller
{
    use MediaDefaultPhotos;
    public function index(){
        $Homedata = DigisolHome::all();

        foreach ($Homedata as $index=> $home ){
            switch($home->type){
                case 1:
                    $data["Header"]=[
                        "id"=>$home->id,
                        "title"=>$home->getTitleAttribute(),
                        "Description"=>$home->getDescriptionAttribute()
                    ];
                    break;
                case 2:
                    $data["Body"]=[
                        "id"=>$home->id,
                        "title"=>$home->getTitleAttribute(),
                        "Description"=>$home->getDescriptionAttribute(),
                    ];
                    break;
                case 3:
                    $data["Testimonials"][]=[
                        "id"=>$home->id,
                        "title"=>$home->getTitleAttribute(),
                        "Description"=>$home->getDescriptionAttribute(),
                        "Date"=>$home->date ?? null,
                        "IMAGE"=>$home->getFirstMediaFile() ?  $home->getFirstMediaFile()->url : null,
                    ];
                    break;
            }


        }

        return JsonResponse::data($data)->send();
    }
}
