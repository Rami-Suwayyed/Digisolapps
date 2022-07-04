<?php

namespace App\Http\Controllers\Web\Ajax;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\CategoryApps;
use Illuminate\Http\Request;

class CategoryAppsAjaxController extends Controller
{



    public function change_status(Request $request){
        try {
            $subject = CategoryApps::find($request->id);
            $subject->status = (bool)$request->value;
            $subject->save();
            return JsonResponse::success()->send();
        }catch (\Exception $exception){
            return JsonResponse::error()->send();
        }
    }

}
