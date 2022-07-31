<?php
namespace App\Http\Controllers\Web\Admin;
use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Order;
use App\Models\User;
use App\Repositories\DashboardRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public DashboardRepository $repository;
    public function __construct(DashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(){
        $this->permissionsAllowed("view-dashboard");
        $data["counter"] = $this->repository->getAllCounters();
        return view("admin.dashboard.index", $data);
    }



    public function FirstLogin(){
        return view("admin.first_login");
    }




    public function Completed(Request $request){
        $manager = Admin::find(Auth::user()->id);
        $result = $this->repository->CompletedValidationProfile($request, $manager);
        if($result["fails"])
            return redirect()->route("admin.first.login")->withInput($request->all())->withErrors($result["errors"]);
        $this->repository->saveCompleted($manager, $request);

        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Employee Been Updated Successfully");
        Dialog::flashing($message);
//        Auth::logout();
        return redirect()->route('admin.dashboard.index');
    }






}
