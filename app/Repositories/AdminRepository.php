<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Models\AdminRelatedRole;
use App\Models\RegisteredEmail;
use App\Rules\HashMatching;
use App\Rules\PasswordPattern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Notifications\Registered;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AdminRepository
{

    public function getAllManagers(){
        return Admin::where("is_super_admin", 0)->get();
    }

    public function getManagerById($managerId) : Admin{
        return Admin::where(["is_super_admin" => 0, "id" => $managerId])->firstOrFail();
    }

    public function rules(){
        return [
            "full_name"         => ["required", "max:255"],
            "email"             => ["required","email", "unique:admins"],
            "role"              => ["required", "exists:admin_roles,id"]
        ];
    }
    public function Profilerules(){
        return [
            "full_name"         => ["required", "max:255"],
            "username"          => ["required", "max:255", "alpha_num", "unique:admins"],
            "email"             => ["required","email", "unique:admins"],
            "password"          => [ "required"],
            "confirm_password"  => [ "required", "same:password"],
            "profile_photo"     => ["required", "image"],
        ];
    }
    public function Completedrules(){
        return [
            "password"          => [ "required", new PasswordPattern()],
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

    public function createValidation(Request $request): array
    {
        return $this->validation($request, $this->rules());
    }

    public function updateValidation(Request $request, $admin): array
    {
        $rules = $this->rules();
        if($request->username === $admin->username)
            $rules["username"] = [];
        if($request->email === $admin->email)
            $rules["email"] = [];
        if(!$request->profile_photo)
            $rules["profile_photo"] = [];
        if($request->old_password || $request->password || $request->confirm_password)
            $rules["old_password"] = ["required", new HashMatching($admin->password)];
        else
            $rules["password"] = $rules["confirm_password"] = [];
        return $this->validation($request, $rules);
    }
    public function updateValidationProfile(Request $request, $admin): array
    {
        $rules = $this->Profilerules();
        if($request->username === $admin->username)
            $rules["username"] = [];
        if($request->email === $admin->email)
            $rules["email"] = [];
        if(!$request->profile_photo)
            $rules["profile_photo"] = [];
        if($request->old_password || $request->password || $request->confirm_password){
            $rules["old_password"] = ["required", new HashMatching($admin->password)];
            $rules["password"] = ["required", Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()];
        }else{
            $rules["password"] = $rules["confirm_password"] = [];
        }
        return $this->validation($request, $rules);
    }


    public function updateProfile(Admin $manager, Request $request){
        $data = $request->only("username",  "full_name", "email", "password");
        if($request->file("profile_photo")){
            $manager->removeAllGroupFiles("main");
            $data["profile_photo"] = $request->file("profile_photo");
        }
        $this->saveProfile($manager, $data);
    }

    public function saveProfile(Admin $manager, $data){
        $manager->full_name = $data["full_name"];
        $manager->username = $data["username"];
        $manager->email = $data["email"];
        if(isset($data["password"]))
            $manager->password = $data["password"];
        $manager->save();
        if(isset($data["profile_photo"]))
            $manager->saveMedia($data["profile_photo"]);
    }


    public function createManager(Request $request){
        $manager = new Admin();
        $manager->is_super_admin = 0;

        $data = $request->only("full_name", "email", "role");
        $password = generateRandomStringAndNumber(8);
        $manager->password = $password ;
        $manager->username = "Manager_" . Str::random(3) . $manager->id;
        $manager->first_password = $password;
        $this->saveManager($manager, $data);
        $relatedRole = new AdminRelatedRole();
        $relatedRole->admin_id = $manager->id;



        $registeredManager = new RegisteredEmail();
        $registeredManager->media_type = 'admin';
        $registeredManager->type_id = $manager->id;
        $registeredManager->url = "/en/admin/email/verification";
        $registeredManager->full_name =$manager->full_name;
        $registeredManager->username = $manager->username;
        $registeredManager->password = $password;
        $registeredManager->email = $manager->email;
        $registeredManager->status = 0;
        $registeredManager->save();
//        $arr = [ 'name' => $manager->full_name  ,'url'=>"/en/admin/email/verification",'username' => $manager->username  ,'Password' => $password ,'email' => $manager->email ];
//        $manager->notify(new Registered($arr));
        $this->saveAdminRelatedRole($relatedRole, $request->role);

        Session::flash("Manager_register_info", [
            "full_name" => $manager->full_name,
            "username" => $manager->username,
            "email" => $manager->email,
            "password" => $password]);
    }

    public function updateManager(Admin $manager, Request $request){
        $data = $request->only("username",  "full_name", "email", "password", "role");
        if($request->file("profile_photo")){
            $manager->removeAllGroupFiles("main");
            $data["profile_photo"] = $request->file("profile_photo");
        }
        $this->saveManager($manager, $data);
        $this->saveAdminRelatedRole($manager->relatedRole, $request->role);
    }

    public function saveManager(Admin $manager, $data){
        $manager->full_name = $data["full_name"];
        $manager->email = $data["email"];
        $manager->save();
    }



    public function saveAdminRelatedRole($relatedRole, $roleId){
        $relatedRole->role_id = $roleId;
        $relatedRole->save();
    }


    public function CompletedValidationProfile(Request $request, $admin): array
    {
        $rules = $this->Completedrules();
        if($request->old_password || $request->password || $request->confirm_password)
            $rules["old_password"] = ["required", new HashMatching($admin->password)];
        else
            $rules["password"] = $rules["confirm_password"] = [];
        return $this->validation($request, $rules);
    }

    public function saveCompleted(Admin $manager, $data){
        $manager->password = $data["password"];
        $manager->completed = 1;
        $manager->save();

    }

}
