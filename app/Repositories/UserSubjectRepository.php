<?php

namespace App\Repositories;


use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Resources\UserSubjectResource;
use App\Models\Subject;
use App\Models\UserSubject;
use App\Rules\CheckIfSubjectForSameTypeOfUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserSubjectRepository
{

    public function rules(bool $fixedPrice, $hasPrice, $user) : array{
        $rules = $hasPrice ?
                    ( $fixedPrice ? [
                    "price" => ["required", "numeric", "min:0"]
                    ] : [
                        "price_from" => ["required", "numeric", "min:0"],
                        "price_to" => ["required", "numeric", "gt:price_from"]
                    ] )
                : [];
        $rules["subject"] = ["required", new CheckIfSubjectForSameTypeOfUser($user)];
        return $rules;
    }

    public function addSubjectToUser(Request $request, $user){
        $hasPrice = $user->type === "eo" || $user->type === "ml" ;
        $isFixedPrice = $request->price ? true : false;
        $valid = Validator::make( $request->all(), $this->rules($isFixedPrice, $hasPrice, $user));
        if($valid->fails())
            return JsonResponse::validationErrors($valid->errors()->messages())->send();

        $userSubject = new UserSubject();
        $userSubject->subject_id = $request->subject;
        $userSubject->user_id = $user->id;
        if($hasPrice){
            if($isFixedPrice){
                $userSubject->price = $request->price;
                $userSubject->price_type = "f";
            }else{
                $userSubject->price_from = $request->price_from;
                $userSubject->price_to = $request->price_to;
                $userSubject->price_type = "r";
            }
        }else
            $userSubject->price_type = "u";

        $userSubject->save();
        return JsonResponse::success()->send();
    }

    public function getAllSubjectsForUser(Request $request, $user){
        Subject::$GET_FULL_NAME = true;
        $subjects = UserSubject::where("user_id", $user->id)->get();
        $data["subjects"] = UserSubjectResource::collection($subjects);
        return JsonResponse::data($data)->send();
    }

}
