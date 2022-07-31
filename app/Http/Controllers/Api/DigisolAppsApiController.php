<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Helpers\Media\Src\MediaDefaultPhotos;
use App\Http\Controllers\Controller;
use App\Http\Resources\DigisolAppResource;
use App\Models\AppsPage;


class DigisolAppsApiController extends Controller
{
    use MediaDefaultPhotos;
    public function index(){
        $Apps = AppsPage::where('company','digisol')->get();

        $data["Apps"] = DigisolAppResource::collection($Apps);

        return JsonResponse::data($data)->send();
    }
}
