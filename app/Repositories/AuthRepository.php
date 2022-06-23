<?php

namespace App\Repositories;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Models\Student;
use App\Models\SharingCode;
use App\Models\Teacher;
use App\Models\User;
use App\Rules\BeforeTime;
use Carbon\Carbon;
use Firebase\Auth\Token\Exception\InvalidToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Kreait\Laravel\Firebase\Facades\Firebase;

class AuthRepository
{
    protected UserLocationRepository $userLocationRepository;
    protected UserRepository $userRepository;
    public function __construct(){
        $this->userLocationRepository = new UserLocationRepository();
        $this->userRepository = new UserRepository();
    }

    public function registerRules(Request $request): array
    {
        $rules = [
            "full_name"         => ["required", "max:255"],
            "firebase_uid"      =>  ["required", "unique:students"],
            "email"             => ["required", "email"],
            "phone_number"      => ["required", "unique:students"],
            "gender"            => ["required", "in:1,2"],
        ];
//        $yearBefore = (int)date("Y", time()) - 15;
//        $monthBefore = date("m", time());
//        $dayBefore = date("d", time());
//        $rules["birth_date"] = ["required", "date", new BeforeTime("{$yearBefore}-{$monthBefore}-$dayBefore")];
        return $rules;
    }

    public function registerValidation(Request $request): array
    {
        $registerRules = $this->registerRules($request);
        $userLocationRules = $this->userLocationRepository->rules();
        $rules = $registerRules + $userLocationRules;
        $valid = Validator::make($request->all(),  $rules);
        $result["fails"] = false;
        if($valid->fails()){
            $result["fails"] = true;
            $result["messages"] = $valid->errors()->messages();
        }
        return $result;
    }

    public function createToken(User $user): string
    {
        $token = $user->createToken(env("TOKEN_KEY"));
        $tokenObj = $token->token;
        $tokenObj->expires_at = Carbon::now()->addWeeks(4);
        $tokenObj->save();
        return $token->accessToken;
    }

    public function login(User $user): array
    {
        $data["token"] = $this->createToken($user);
        $data["user"] = [
            "full_name" => $user->full_name,
            "user_type" => $user->type,
            "email" => $user->email,
            "photo_profile" => $user->getFirstMediaFile("profile_photo") ? $user->getFirstMediaFile("profile_photo")->url : null,
        ];
        if($user->type == "t" && $user->teacher->completed == 1){
            $user->teacher->login = 1;
            $user->teacher->save();
        }
        return $data;
    }

    /**
     * @throws \Exception
     */
    public function loginStudent(Request $request): array
    {
        $auth = Firebase::auth();
        $firebaseToken = $request->firebase_token;

        try { // Try to verify the Firebase credential token with Google
            $verifiedIdToken = $auth->verifyIdToken($firebaseToken);
            $uid = $verifiedIdToken->claims()->get('sub');
            $student = Student::where('firebase_uid', $uid)->first();
            if($student){
                $user = $student->user;
                if($user->status == 0){
                    throw new \Exception("This account has been suspended by the administrator", 403);
                }
                $data = $this->login($user);
                if($request->device_token){
                    $user->device_token = $request->device_token;
                    $user->save();
                }
                $data["user"]["phone_number"] = $student->phone_number;
                return $data;
            }else{
                throw new \Exception("user not found", 400);
            }

        } catch (\InvalidArgumentException $e) {
            throw new \Exception($e->getMessage(), 500);
        } catch (InvalidToken $e) { // If the token is invalid (expired ...)
            throw new \Exception('token not valid', 401);
        }
    }

    /**
     * @throws \Exception
     */
    public function loginTeacher(Request $request): array
    {
        $field = filter_var($request->login_field, FILTER_VALIDATE_EMAIL) ? "email" : "username";

        if($field == "email"){
            $user = User::where(["email" => $request->login_field, "type" => "t"])->first();
            if(!$user)
                throw new \Exception ("user not found", 500);
            elseif($user->teacher->login == 1)
                throw new \Exception("This account has been login", 403);
        }else{
            $teacher = Teacher::where("username", $request->login_field)->first();
            if(!$teacher)
                throw new \Exception("user not found", 404);
            elseif($teacher->login == 1)
                throw new \Exception("This account has been login", 403);

            $user = $teacher->user;
        }
        if($user->teacher->isPasswordMatch($request->password)){
            if($request->device_token){
                $user->device_token = $request->device_token;
                $user->longitude = $request->longitude ?? '35.44027630332068';
                $user->latitude = $request->latitude ?? '31.44241928585525';
                $user->save();
            }
            $data = $this->login($user);
            $data['user']['completed'] = $user->teacher->completed;
            $data['user']['type_teacher'] = $user->teacher->type;
            return $data;
        }else{
            throw new \Exception("password incorrect", 400);
        }
    }


    public function register($request): array
    {
        $user = new User();
        $user->type = "u";
        $this->userRepository->saveUser($user, $request);
        $this->userRepository->createLiveLocation($user->id);
        $student = new Student();
        $student->phone_number = $request->phone_number;
        $student->firebase_uid = $request->firebase_uid;;
        $student->user_id = $user->id;
        $student->save();
        $sharing = new SharingCode();
        $sharing->user_id = $user->id;
        $sharing->code = generateRandomStringAndNumber(6);
        $sharing->first_order = 0;
        $sharing->save();
        $this->userLocationRepository->createLocation($request, $user);
        $data = $this->login($user);
        $data["user"]["phone_number"] = $student->phone_number;
        return $data;
    }
}
