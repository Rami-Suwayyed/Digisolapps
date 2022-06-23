<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SlidersController extends Controller
{
    public function index(){
        $Sliders = Slider::all();
        $data = [];
        foreach ($Sliders as $index=> $slider) {
            $data[$index]['image'] = $slider->getFirstMediaFile()->url;
        }
        return JsonResponse::data($data)->send();
    }
}
