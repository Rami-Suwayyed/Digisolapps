<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Helpers\Media\Src\MediaDefaultPhotos;
use App\Http\Controllers\Controller;
use App\Http\Resources\DigisolAppResource;
use App\Models\DigisolApp;
use App\Models\Slider;
use Illuminate\Http\Request;

class DigisolAppsApiController extends Controller
{
    use MediaDefaultPhotos;
    public function index(){
        $Apps = DigisolApp::all();
    
        $data["Apps"] = DigisolAppResource::collection($Apps);

        return JsonResponse::data($data)->send();
    }
}
