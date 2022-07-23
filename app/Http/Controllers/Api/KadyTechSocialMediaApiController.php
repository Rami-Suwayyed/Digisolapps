<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShowSocialMediaResource;
use App\Models\KadyTechSocialMedia;

class KadyTechSocialMediaApiController extends Controller
{
    public function index()
    {
        $SocialMedia = KadyTechSocialMedia::all();
        return JsonResponse::data(ShowSocialMediaResource::collection($SocialMedia))->send();
    }
}
