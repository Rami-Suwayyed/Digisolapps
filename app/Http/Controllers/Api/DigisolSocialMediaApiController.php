<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShowSocialMediaResource;
use App\Models\SocialMedia;

class DigisolSocialMediaApiController extends Controller
{
    public function index()
    {
        $SocialMedia = SocialMedia::where('company','digisol')->get();
        return JsonResponse::data(ShowSocialMediaResource::collection($SocialMedia))->send();
    }
}
