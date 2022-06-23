<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Repositories\AdminRepository;
use App\Rules\HashMatching;
use App\Rules\PasswordPattern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    protected AdminRepository $repository;
    public function __construct(AdminRepository $repository){
        $this->repository = $repository;
    }

    public function index(){
        $data['admin'] = Admin::find(Auth::user()->id);
        return view("admin.profile.index", $data);
    }

    public function Setting(){
        $data['admin'] = Admin::find(Auth::user()->id);
        return view("admin.profile.edit", $data);
    }


    public function update(Request $request){
//        dd($request->all());
        $manager = Admin::find(Auth::user()->id);
        $result = $this->repository->updateValidationProfile($request, $manager);
        if($result["fails"])
            return redirect()->route("admin.profile.Setting")->withInput($request->all())->withErrors($result["errors"]);
        $this->repository->updateProfile($manager, $request);

        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Employee Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.profile.index");
//
//        $valid = Validator::make($request->all(), $this->rules());
//        if($valid->fails())
//            return redirect()->route("admin.profile.index")->withInput($request->all())->withErrors($valid->errors()->messages());


//        $admin = Admin::find(Auth::user()->id);
//        $admin->username = $request->username;
//        $admin->full_name = $request->full_name;
//        $admin->email = $request->email;
//        $admin->save();
//        $message = (new SuccessMessage())->title("Update Successfully")
//            ->body("The Profile information Has Been Update Successfully");
//        Dialog::flashing($message);
//        return redirect()->route("admin.profile.index");
    }

    public function ChangePassword(Request $request){
        $valid= Validator::make($request->all(), $this->rulesPassword());
        if($valid->fails())
            return redirect()->route("admin.profile.index")->withInput($request->all())->withErrors($valid->errors()->messages());
        $admin = Admin::find(Auth::user()->id);
        $admin->password = $request->password;
        $message = (new SuccessMessage())->title("Update Successfully")
            ->body("The Profile information Has Been Update Successfully");
        Dialog::flashing($message);
        $admin->save();
        return redirect()->route("admin.profile.index");
    }




}
