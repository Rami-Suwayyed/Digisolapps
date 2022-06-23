<?php

namespace App\Repositories;

use App\Models\Commission;
use App\Models\CommissionTeacher;
use App\Models\MainSpecialty;
use App\Models\RegisteredEmail;
use App\Models\SubjectTeacher;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserPromocode;
use App\Notifications\Registered;
use App\Rules\ArrayKeyExists;
use App\Rules\BeforeTime;
use App\Rules\HashMatching;
use App\Rules\PasswordPattern;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TeacherRepository
{

    public function getById($id){
        return Teacher::findOrFail($id);
    }
    public function getStuffTeachers(){
        return User::selectBuilder()->byTeacherType('s')->get();
    }

    public function getSingleTeachers(){
        return User::selectBuilder()->byTeacherType('t')->get();
    }

    public function getStuffForTeacher($userId): array
    {
        $data['leader'] = $user = User::selectBuilder()->byTeacherType('s')->byId($userId)->firstOrFail();
        $data["teachers"] = $user->teacher->staff;
        return $data;
    }

    public function rules(Request $request): array
    {
        $rules = [
            "full_name"                 => [ "required", "max:255"],
            "email"                     => [ "email", "unique:users", "nullable"],
            "experience_years"          => [ "numeric", "nullable"],
            "gender"                    => ["required", "in:1,2"],
            "subjects"                  => ["required"],
            "subjects.*"                => ["required"],
            "main_specialty"                => ["required"],
            "profile_photo"             => ["image"],
            "type_education"                 => ["required", "in:1,0"],
            "university"                 => ["required"],
            "Higher_education"                 => ["required"],
        ];
        return $rules;
    }


    public function rulesupdate(Request $request): array
    {
        $rules = [
            "full_name"                 => [ "required", "max:255"],
            "email"                     => [ "email", "unique:users", "nullable"],
            "experience_years"          => [ "numeric", "nullable"],
            "gender"                    => ["required", "in:1,2"],
            "main_specialty"            => ["required"],
            "subjects"                  => ["required"],
            "subjects.*"                => ["required"],
        ];
        return $rules;
    }

    public function getAll($user_id){
        return CommissionTeacher::orderBy("price_from", "asc")->where("teacher_id", $user_id)->get();
    }

    public function priceRules(): array
    {
        return [
            "price_from" => ["required"],
            "price_to" => ["required"],
            "application_commission" => ["required"],
            "price_from.*" => ["required", "numeric"],
            "price_to.*" => ["required", "numeric", "gt:price_from.*"],
            "application_commission.*" => ["required", "numeric"],
            "price_from_infinity" => ["required", "numeric"],
            "commission_infinity" => ["required", "numeric"],
        ];
    }

    public function columns(): array
    {
        return [
            "price_from.*" => "price from",
            "price_to.*" =>  "price to",
            "application_commission.*" =>  "commission",
            "price_from_infinity" =>  "price from",
            "commission_infinity" =>  "commission"
        ];
    }

    public function checkIsPricesIsValid(Request $request): array
    {
        $messages = [];
        $prices = [];

        $prices["from"] = $request->price_from;
        $prices["to"] = $request->price_to;
        $validPrice = 1;
        for($i = 0; $i < count($prices["from"]); $i++){
            if($validPrice != (int)$prices["from"][$i]){
                $messages["price_from.$i"][] = __("The Price Must Be {$validPrice}");
            }
            $validPrice = $prices["to"][$i] + 1;
        }

        if($validPrice != $request->price_from_infinity){
            $messages["price_from_infinity"][] = __("The Price Must Be {$validPrice}");
        }

        $result["fails"] = !empty($messages);
        if($result["fails"])
            $result["errors"] = $messages;

        return $result;
    }

    public function validation(Request $request): array
    {
        $result["fails"] = false;
        $valid = Validator::make($request->all(), $this->priceRules(), [], $this->columns());
        if($valid->fails()){
            $result["fails"] = true;
            $result["errors"] = $valid->errors()->messages();
        }else{
            $result = $this->checkIsPricesIsValid($request);
        }
        return $result;
    }



    public function staffRules(Request $request, Collection $subjects): array
    {
        $rules = $this->rules($request);
        $subjectsIds = [];
        foreach ($subjects as $subject)
            $subjectsIds[$subject->id] = true;

        $rules["subjects.*"][] = new ArrayKeyExists($subjectsIds);
        $rules["profile_photo"] = [ "image"];

        return $rules;
    }


    public function updateRules(Request $request, $user): array
    {
        $rules = $this->rulesupdate($request);
        if($request->username !== $user->teacher->username)
            $rules["username"] = ["required","unique:teachers"];

        if($request->email == $user->email)
            $rules["email"] = [];

        if(!$request->profile_photo)
            $rules["profile_photo"] = [];

        return $rules;
    }
    public function updateStaffRules(Request $request, Collection $services, $user): array
    {
        $rules = $this->staffRules($request, $services);

        if($request->password || $request->confirm_password){
            $rules["password"] =  ["required", new PasswordPattern()];
            $rules["confirm_password"] =  ["required", "same:new_password"];
        }
        if($request->username !== $user->teacher->username)
            $rules["username"] = ["required","unique:teachers"];

        if($request->email == $user->email)
            $rules["email"] = [];

        if(!$request->profile_photo)
            $rules["profile_photo"] = [];


        return $rules;
    }

    public function register(Request $request, $type, $leader = null){
//        dd($request->{"price_from.0"}, $request->price_to);
//        dd($request->subject_price);
        $userRepository = new UserRepository();
        $user = new User();
        $user->type = "t";
        $userRepository->saveUser($user, $request);
        $userRepository->createLiveLocation($user->id);

        $username = "teacher_" . Str::random(3) . $user->id;
        $password = generateRandomStringAndNumber(8);
        $request->request->set("username", $username);
        $request->request->set("password", $password);
        $teacher = new Teacher();
        $teacher->first_password = $password;
        $teacher->user_id = $user->id;
        $teacher->type = $type;
        $teacher->price = $request->teacher_price??0;
        if($leader)
            $teacher->leader_id = $leader->teacher->id;

        $this->saveTeacher($request, $teacher);
            $registeredTeacher = new RegisteredEmail();
            $registeredTeacher->media_type = 'teacher';
            $registeredTeacher->type_id = $user->id;
            $registeredTeacher->url = "https://play.google.com/store";
            $registeredTeacher->full_name = $user->full_name;
            $registeredTeacher->username = $teacher->username;
            $registeredTeacher->password = $password;
            $registeredTeacher->email = $user->email;
            $registeredTeacher->status = 0;
            $registeredTeacher->save();
//        $arr = [ 'name' => $user->full_name  ,'url'=>"/en/admin/email/verification",'username' => $teacher->username  ,'Password' => $password ,'email' => $user->email ];
//        $user->notify(new Registered($arr));
        if($type != "ts") {
            $this->saveCommission(["price_from" => $request->{"price_from.0"}, "price_to" => $request->{"price_to.0"}, "application_commission" => $request->application_commission, "user_id" => $user->id], new CommissionTeacher());
            $lastCommissionIndex = 0;
            $lastCommissionIndex++;
            for ($i = $lastCommissionIndex; $i < count($request->price_from); $i++)
                $this->saveCommission(["price_from" => $request->{"price_from.$i"}, "price_to" => $request->{"price_to.$i"}, "application_commission" => $request->{"commission." . ($i - 1)}, "user_id" => $user->id], new CommissionTeacher());
            $this->saveCommission(["price_from" => $request->price_from_infinity, "price_to" => -1, "application_commission" => $request->commission_infinity, "user_id" => $user->id], new CommissionTeacher());
        }else{
            $commissionTeacher = CommissionTeacher::where("teacher_id", $leader->id)->get();
            foreach ($commissionTeacher as $commission)
                $this->saveCommission(["price_from" => $commission->price_from, "price_to" => $commission->price_to, "application_commission" => $commission->commission, "user_id" => $user->id], new CommissionTeacher());;
        }

        foreach ($request->subjects as $key => $subject){
            DB::table('subjects_teachers')->insert(
                ['subject_id' => $subject, 'teacher_id' => $teacher->id,'price' =>$request->subject_price[$subject],"commission" => $request->subject_Commission_new[$subject],"status" => 1]
            );
        }

            // $teacher->subjects()->attach($subject, ["commission" => 55,"price" => 30,"status" => 1]);

        foreach ($request->main_specialty as $specialty)
            $this->saveMainSpecialty((new MainSpecialty()), $user->id, $specialty);

        Session::flash("teacher_register_info", [
            "full_name" => $user->full_name,
            "username" => $username,
            "email" => $user->email,
            "phone_number" => $user->phone_number,
            "password" => $password]);
    }

    public function saveTeacher(Request $request, Teacher & $teacher){
        $teacher->username = $request->username;
        if($request->password)
            $teacher->password = $request->password;
        $teacher->phone_number_1 = $request->phone_number_1 ?? $teacher->phone_number_1;
        $teacher->phone_number_2 = $request->phone_number_2 ?? $teacher->phone_number_2;
        $teacher->price = $request->teacher_price ?? $teacher->price;
        $teacher->address = $request->address ?? $teacher->address;
        $teacher->experience_years = $request->experience_years ?? $teacher->experience_years;
        $teacher->application_commission = $request->application_commission ?? $teacher->application_commission;
        $teacher->teacher_category = 1;
        $teacher->Higher_education = $request->Higher_education;
        $teacher->type_education = $request->type_education ?? $teacher->type_education;
        $teacher->save();
//        if($request->file("")){
//            $teacher->saveMedia($request->file("university"), "university");
//        }
        if($request->file("experience")){
            if($teacher->getFirstMediaFile("experience"))
                $teacher->removeAllGroupFiles("experience");
            $teacher->saveMedia($request->file("experience"), "experience");
        }
        if($request->file("university")){
            if($teacher->getFirstMediaFile("university"))
                $teacher->removeAllGroupFiles("university");
            $teacher->saveMedia($request->file("university"), "university");
        }
    }

    public function saveMainSpecialty($mainSpecialty, $userId, $mainId){
        $mainSpecialty->teacher_id = $userId;
        $mainSpecialty->main_categories_id = $mainId;
        $mainSpecialty->save();
    }

    public function saveCommission($data, $commission){
        $commission->price_from = $data["price_from"];
        $commission->price_to = $data["price_to"];
        $commission->commission = $data["application_commission"];
        $commission->teacher_id = $data['user_id'];
        $commission->save();
    }

    public function update(Request $request, $user){
        (new UserRepository())->saveUser($user, $request);
        $teacher = $user->teacher;
        $this->saveTeacher($request,$teacher);
//        $subjects = [];
//        foreach ($request->subjects as $subject)
//            $subjects[$subject] = ["commission" => $request->{"subject_commission_" . $subject}];
//        $teacher->subjects()->sync($subjects);
        if($request->subjects && $request->subject_price){
//            dd($request->subjects);
//            dd($request->subjects);
            $tests= SubjectTeacher::where('teacher_id',$teacher->id)->get();
            foreach ( $tests as  $test){
                $test->delete();
            }
            foreach ($request->subjects as $key => $subject){
//                 dd($request->subjects);
                DB::table('subjects_teachers')->insert(
                    ['subject_id' => $subject, 'teacher_id' => $teacher->id,'price' =>$request->subject_price[$subject],"commission" => $request->subject_Commission_new[$subject],"status" => 1]
                );
            }
        }


        $types = $user->getTypesRelatedItAsArray();
        $removeTypes = array_diff($types, $request->main_specialty);
        $createTypes = array_diff($request->main_specialty, $types);
        $user->specialty()->where("teacher_id", $user->id)->whereIn("main_categories_id", $removeTypes)->delete();
        foreach ($createTypes as $type) {
            $typeRelated = new MainSpecialty();
            $typeRelated->main_categories_id = $type;
            $user->specialty()->save($typeRelated);
        }
    }

    public function delete($user){
        $user->removeAllFiles();
        $user->delete();
    }

    public function deleteCommission($id, $user_id): bool
    {
        $commissions = $this->getAll($user_id);
        if(count($commissions) > 2){
            $commission = CommissionTeacher::where([['price_to', '!=', -1], ["teacher_id","=", $user_id], ['id', "=", $id]])->first();
            $maxCommission = CommissionTeacher::where([['price_to', '!=', -1], ["teacher_id","=", $user_id]])
                ->select(DB::raw('max(`price_from`) as max'))->first();
            $maxCommission = $maxCommission ? $maxCommission->max : 1;
            if($commission && $maxCommission == $commission->price_from){
                $commission->delete();
                CommissionTeacher::where([['price_to', -1.00], ["teacher_id","=", $user_id]])->update(["price_from" => $commission->price_from]);
                return true;
            }
        }
        return false;
    }

    public function buildTreeData($tree){
        return true;
    }

}
