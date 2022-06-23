<?php

namespace App\Repositories;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserLiveLocation;
use App\Rules\BeforeTime;
use App\Rules\HashMatching;
use App\Rules\PasswordPattern;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class UserRepository
{
    public function rules(Request $request): array
    {
        $rules = [
            "full_name"         => ["required", "max:255"],
            "firebase_uid"      =>  ["required", "unique:students"],
            "email"             => ["required", "email", "unique:users"],
            "phone_number"      => ["required", "unique:students"],
            "gender"            => ["required", "in:1,2"],
        ];
        $yearBefore = (int)date("Y", time()) - 15;
        $monthBefore = date("m", time());
        $dayBefore = date("d", time());
        $rules["birth_date"] = ["required", "date", new BeforeTime("{$yearBefore}-{$monthBefore}-$dayBefore")];
        return $rules;
    }


    public function updateRules(Request $request, $user): array
    {
        $rules = $this->rules($request);
        $rules["phone_number"] = $request->phone_number ? ($user->student->phone_number != $request->phone_number ? $rules["phone_number"] : []) : [];
        $rules["firebase_uid"] = [];
        $rules["full_name"] = $request->full_name ? $rules["full_name"] : [];
        $rules["email"] = $request->email ? ($user->email != $request->email ? $rules["email"] : []) : [];
        $rules["gender"] = $request->gender ? $rules["gender"] : [];
        $rules["birth_date"] = $request->birth_date ? $rules["birth_date"] : [];
        return $rules;
    }


    public function register($request,  UserLocationRepository $userLocationRepository): array
    {
        $user = new User();
        $user->type = "u";
        $this->saveUser($user, $request);
        $student = new Student();
        $student->phone_number = $request->phone_number;
        $student->firebase_uid = $request->firebase_uid;;
        $student->user_id = $user->id;
        $student->save();
        $this->createLiveLocation($user->id);
        $userLocationRepository->createLocation($request, $user);
        $token = $user->createToken(env("TOKEN_KEY"));
        $tokenObj = $token->token;
        $tokenObj->expires_at = Carbon::now()->addWeeks(4);
        $tokenObj->save();
        $data["token"] = $token->accessToken;
        return $data;
    }


    public function saveUser(User &$user, Request $request){
        $user->full_name = $request->full_name ?: $user->full_name;
        $user->email = $request->email ?? $user->email;
        $user->gender = $request->gender ?? $user->gender;
        $user->birth_date = $request->birth_date ?? $user->birth_date;
        $user->longitude = $request->longitude ?? '35.83027630332068';
        $user->latitude = $request->latitude ?? '31.99241928585525';
        $user->save();

        if($request->file("profile_photo")){
            if($user->getFirstMediaFile("profile_photo"))
                $user->removeAllFiles();
            $user->saveMedia($request->file("profile_photo"), "profile_photo");
        }
    }

    public function createLiveLocation($userId){
        $liveLocation = new UserLiveLocation();
        $liveLocation->lat = 0;
        $liveLocation->lng = 0;
        $liveLocation->available = 0;
        $liveLocation->user_id = $userId;
        $liveLocation->save();
    }




}
