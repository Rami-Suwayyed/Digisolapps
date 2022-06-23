<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\AboutAs;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function index(){
        $about = AboutAs::all();
        $data['about'] = $about[0]->getNameAttribute();
        return JsonResponse::data($data)->send();
    }
}
