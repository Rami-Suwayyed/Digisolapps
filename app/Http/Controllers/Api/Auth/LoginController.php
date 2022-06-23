<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\AuthRepository;
use Carbon\Carbon;
use Firebase\Auth\Token\Exception\InvalidToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Laravel\Firebase\Facades\Firebase;

class LoginController extends Controller
{
    protected $repository;
    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;

    }

    public function login(Request $request){
        try {
            switch ($request->type){
                case "student":

                    $data = $this->repository->loginStudent($request);
                    break;
                case "teacher":
                    $data = $this->repository->loginTeacher($request);
                    break;
                default:
                    return JsonResponse::error()->message("not found url")->send();
                break;
            }
            return JsonResponse::data($data)->send();
        }catch (\Exception $exception){
            return JsonResponse::error()->message($exception->getMessage())->changeCode($exception->getCode() >= 400 ? $exception->getCode() : 500)->send();
        }
    }


    public function logout(Request $request){
        $user = Auth::guard("api")->user();
        if($request->type === "all") {
            if($user->type == "t"){
                $user->teacher->login = 0;
                $user->teacher->save();
            }
            $user->authAccessToken()->delete();
        }else {
            $user->token()->revoke();
            if($user->type == "t"){
                $user->teacher->login = 0;
                $user->teacher->save();
            }
        }
        return JsonResponse::success()->send();
    }



    public function testLogin(Request $request){
        $user = User::find($request->user_id);
        if($user->status == 0){
            throw new \Exception("This account has been suspended by the administrator", 401);
        }
        if($user){
            $token = $user->createToken(env("TOKEN_KEY"));
            $tokenObj = $token->token;
            $tokenObj->expires_at = Carbon::now()->addWeeks(4);
            $tokenObj->save();
            $data["token"] = $token->accessToken;
            $data["user"] = [
                "full_name" => $user->full_name,
                "user_type" => $user->type,
                "email" => $user->full_name,
                "phone_number" => $user->phone_number,
                "photo_profile" => $user->getFirstMediaFile("profile_photo") ? $user->getFirstMediaFile("profile_photo")->url : null,
            ];
            return JsonResponse::data($data)->message("login success")->send();
        }else{
            return JsonResponse::error()->message("user not found")->changeCode(401)->changeStatusNumber("S401")->send();
        }
    }
}
