<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DigisolController extends Controller
{
    public function index()
    {
        return view("admin.digisol.index");
    }
}
