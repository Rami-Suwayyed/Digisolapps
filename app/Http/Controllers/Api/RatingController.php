<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{

    public function rules(){
        return [
            "teacher_id" => ["required"],
            "number_rating" => ['required', "in:0,1,2,3,4,5"],
            "order_id" => ['required'],
            "reason" => ['required']
        ];
    }

    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails())
            return JsonResponse::validationErrors($valid->errors()->messages())->send();
        $Ollrating = Rating::where('order_id', $request->order_id)->get('id');
//      dd($Ollrating);
        if($Ollrating->isEmpty()){
            $Rating = new Rating();
            $Rating->user_id = Auth::user()->id;
            $Rating->teacher_id = $request->teacher_id;
            $Rating->number_rating = $request->number_rating;
            $Rating->order_id = $request->order_id;
            $Rating->reason = $request->reason;
            $Rating->save();
            return JsonResponse::success()->message("Thank you for rating")->send();
        }else{
            return JsonResponse::error()->message("You have already evaluated the Order before")->send();
        }
    }

}
