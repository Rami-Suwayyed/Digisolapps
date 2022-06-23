<?php

namespace App\Repositories;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Rules\HashMatching;
use App\Rules\PasswordPattern;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class ProfileRepository
{
    protected UserRepository $userRepository;
    protected TeacherRepository $teacherRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->teacherRepository = new TeacherRepository();
    }

    public function rules(Request $request, $user): array
    {
        $rules = $user->type == "t" ? $this->teacherRepository->updateRules($request, $user)
                                    : $this->userRepository->updateRules($request, $user);

        if($user->type == "t"){
            $rules["phone_number_1"] = $rules["phone_number_2"] = $rules["address"]
            = $rules["experience_years"] = $rules["gender"] =  $rules["subjects"]
            = $rules["application_commission"] = $rules["birth_date"] = $rules["subjects.*"] = [];

            if($request->password || $request->confirm_password || $request->old_password){
                $rules["old_password"] =  ["required", new HashMatching($user->teacher->password)];
                $rules["password"] =  ["required", new PasswordPattern()];
                $rules["confirm_password"] =  ["required", "same:password"];
            }
            if(!$request->username)
                $rules["username"] = [];

        }else{
            $rules["firebase_uid"] = [];
            $rules["gender"] = $request->gender ? $rules["gender"] : [];
            $rules["birth_date"] = $request->birth_date ? $rules["birth_date"] : [];
            $rules["phone_number"] = $request->phone_number ? ($user->student->phone_number != $request->phone_number ? $rules["phone_number"] : []) : [];
        }

        $rules["email"] = $request->email ? ($user->email != $request->email ? $rules["email"] : []) : [];
        $rules["full_name"] = $request->full_name ? $rules["full_name"] : [];
        return $rules;
    }

    public function changePasswordRules(): array
    {
        return [
            "password" => ["required", new PasswordPattern()],
            "confirm_password" => ["required", "same:password"]
        ];
    }

    /**
     * @throws \Exception
     */
    public function changeFirstPasswordTeacher($password, $teacher){
        if($teacher->completed)
            throw new \Exception("the first password is changed");

        $teacher->password = $password;
        $teacher->login = 0;
        $teacher->completed = 1;
        $teacher->save();
    }

    public function changeAcceptOrderStatus($status, $teacher){
        $teacher->accept_order = (bool)$status;
        $teacher->save();
    }

    public function saveProfile(Request $request, $user){
        $this->saveUser($request, $user);
        switch ($user->type){
            case "t": $this->saveTeacher($request, $user->teacher); break;
            case "u": $this->saveStudent($request, $user->student); break;
        }
    }



    public function saveStudent(Request $request, Student & $student){
        $student->phone_number = $request->phone_number ?? $student->phone_number;
        if($request->phone_number) {
            $phonNumberExists = Student::where("phone_number", $request->phone_number)->get();
            if($phonNumberExists->isNotEmpty())
                return JsonResponse::error()->message("Already Exists")->send();
            $student->firebase_uid = $request->firebase_uid;
        }
        $student->save();
    }

    public function saveTeacher(Request $request, Teacher &$teacher){
        $teacher->username = $request->username ?? $teacher->username;
        if($request->password)
            $teacher->password = $request->password;
        $teacher->save();
    }

    public function saveUser(Request $request, $user){
        if($user->type === "u"){
            $user->gender = $request->gender ?? $user->gender;
            $user->birth_date = $request->birth_date ?? $user->birth_date;
        }
        $user->full_name = $request->full_name ?? $user->full_name;
        $user->email = $request->email ?? $user->email;
        $user->save();
        if($request->file("profile_photo")){
            if($user->getFirstMediaFile("profile_photo"))
                $user->removeAllFiles();
            $user->saveMedia($request->file("profile_photo"), "profile_photo");
        }
    }


}
