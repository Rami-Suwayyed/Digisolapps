<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\DigisolApp;
use App\Models\Slider;
use Illuminate\Http\Request;

class DigisolAppsApiController extends Controller
{
    public function index(){
        $Apps = DigisolApp::all();
        $data = [];
        foreach ($Apps as $index=> $App) {
            $data[$index]['id'] = $App->id;
            $data[$index]['name'] = $App->getNameAttribute();
            $data[$index]['Description'] = $App->getDescriptionAttribute();
            $data[$index]['image-icon'] =  $App->getFirstMediaFile("icon")->url ?? null;
            $data[$index]['image-phone'] =  $App->getFirstMediaFile("phone")->url ?? null;
            $data[$index]['image-background'] =  $App->getFirstMediaFile("background")->url ?? null;
        }
        return JsonResponse::data($data)->send();
    }
}
