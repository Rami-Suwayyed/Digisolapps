<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class KadyTechController extends Controller
{
    public function index()
    {
        return view("admin.KadyTech.index");
    }
}
