<?php

namespace App\Http\Controllers\Web\Ajax;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagerController extends Controller
{
    public function index(Request $request){
        $managers = Admin::where('id',$request->id)->get();
        return JsonResponse::data($managers)->send();
    }

    public function changeType(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->permissionsAllowed( "update-teachers-information");

        $user = User::find($request->user_id);
        if($user){
            $teacher = $user->teacher;
            if(in_array($request->teacher_category, ["s", "t"]))
                $teacher->type = $request->teacher_category;
            else if($request->teacher_category == "ts"){
                $teacher->type = $request->teacher_category;
                $teacher->leader_id = $request->teacher_leader;
            }
            $teacher->update();
        }
        return JsonResponse::success()->send();
    }

    public function changeStatus(Request $request){
//        dd("sads");
        try {
            $user = Admin::find($request->id);
            $user->status = (bool)$request->value;
            $user->save();
            return JsonResponse::success()->send();
        }catch (\Exception $exception){
            return JsonResponse::error()->send();
        }
    }
}
