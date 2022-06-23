<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Rules\HashMatching;
use App\Rules\PasswordPattern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class DashboardRepository
{
    public function getAllCounters()
    {
        $sql = "SELECT  (SELECT COUNT(id) FROM digisol_contact )  as contactus";
        return DB::select($sql)[0];
    }

    public function Completedrules(){
        return [
            "password"          => [ "required"],
            "confirm_password"  => [ "required", "same:password"],
        ];
    }

    protected function validation(Request $request, array $rules): array
    {
        $result["fails"] = false;
        $valid = Validator::make($request->all(), $rules);
        if($valid->fails()){
            $result["fails"] = true;
            $result["errors"] = $valid->errors()->messages();
        }
        return $result;
    }

    public function CompletedValidationProfile(Request $request, $admin): array
    {
        $rules = $this->Completedrules();
        if($request->password || $request->confirm_password)
            $rules["password"] = ["required", Password::min(8)
                ->mixedCase()];
        else
            $rules["password"] = $rules["confirm_password"] = [];
        return $this->validation($request, $rules);
    }

    public function saveCompleted(Admin $manager, $data){
        $manager->password = $data["password"];
        $manager->completed = 1;
        $manager->email_verified_at = now();
        $manager->save();

    }

}
