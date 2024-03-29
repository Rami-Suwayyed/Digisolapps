<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Helpers\Media\Src\MediaDefaultPhotos;
use App\Http\Controllers\Controller;
use App\Models\HomePage;
use App\Models\KadyTechHome;


class KadyTechHomeApiController extends Controller
{
    use MediaDefaultPhotos;
    public function index(){
        $Homedata = HomePage::where('company','kadytech')->get();

        foreach ($Homedata as $index=> $home ){
            $data["OurStory"]=[
                "id"=>$home->id,
                "title"=>$home->getTitleAttribute(),
                "Description"=>$home->getDescriptionAttribute()
            ];
        }

        return JsonResponse::data($data)->send();
    }
}
