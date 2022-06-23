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






    public function RequestRate(Request $request)
    {
        $data["status"] = $request->status ?? "all";
        $data["totalUsersApp"] = User::count();
        $data["totalOrders"] = Order::count();

        if($request->status == Null || $request->status == "Monthly") {
            $rows = Order::select([DB::raw("COUNT(*) as total_orders"), DB::raw("Month(created_at) as month")])
                ->where(DB::raw("YEAR(created_at)"), DB::raw("YEAR(CURRENT_TIMESTAMP)"))
                ->groupby(DB::raw("Month(created_at)"))->get();
            $cuurentMonth = date("n", time());
            for ($month = 1; $month <= $cuurentMonth; $month++) {
                $orders[date("F", mktime(0, 0, 0, $month, 10))] = ["total_orders" => 0];
            }
            foreach ($rows as $row) {
                $orders[date("F", mktime(0, 0, 0, $row["month"], 10))]["total_orders"] = $row["total_orders"];
            }
        }elseif($request->status == "Weekly"){
            $rows = Order::select([DB::raw("COUNT(*) as total_orders"), DB::raw("created_at as month")])
                ->where(DB::raw("YEAR(created_at)"), DB::raw("YEAR(CURRENT_TIMESTAMP)"))
                ->groupby(DB::raw("Week(created_at)"))->get();

            foreach ($rows as $row) {
                $orders[date("M d",  strtotime($row["month"]))]["total_orders"] = $row["total_orders"];
            }
        }elseif($request->status == "Yearly"){
            $rows = Order::select([DB::raw("COUNT(*) as total_orders"), DB::raw("YEAR(created_at) as month")])
                ->groupby(DB::raw("YEAR(created_at)"))->get();
            for ($month = 1; $month <= count($rows); $month++) {
                $orders[date("Y", mktime(0, 0, 0, $month, 10))] = ["total_orders" => 0];
            }
            foreach ($rows as $row) {
                $orders[$row["month"]]["total_orders"] = $row["total_orders"];
            }
        }

        $data["orders"] = $orders;


        return view("admin.report.Request_rate", $data);


    }





}
