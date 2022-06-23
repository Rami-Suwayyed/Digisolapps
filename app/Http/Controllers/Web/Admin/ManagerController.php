<?php

namespace App\Http\Controllers\Web\Admin;

use App\Exports\ExeclExport;
use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\User;
use App\Notifications\Registered;
use App\Repositories\AdminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class ManagerController extends Controller
{
    protected AdminRepository $repository;
    public function __construct(AdminRepository $repository){
        $this->repository = $repository;
    }

    public function index(){
        $this->permissionsAllowed("admin-control");
        $data["managers"] = $this->repository->getAllManagers();
        return view("admin.managers.index", $data);
    }


    public function create()
    {
        $this->permissionsAllowed("admin-control");

        $data["roles"] = AdminRole::all();
        return view("admin.managers.create", $data);
    }

    public function store(Request $request)
    {
        $this->permissionsAllowed("admin-control");

        $result = $this->repository->createValidation($request);
        if($result["fails"])
            return redirect()->route("admin.managers.create")->withInput($request->all())->withErrors($result["errors"]);
        $this->repository->createManager($request);

        $message = (new SuccessMessage())->title("Created Successfully")
            ->body("The Manager Been Created Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.managers.index");
    }

    public function edit(Request $request){
        $this->permissionsAllowed("admin-control");

        $data["manager"] = $this->repository->getManagerById($request->manager);
        $data["roles"] = AdminRole::all();

        return view("admin.managers.edit", $data);
    }

    public function update(Request $request, $id){
        $this->permissionsAllowed("admin-control");

        $manager = $this->repository->getManagerById($request->manager);
        $result = $this->repository->updateValidation($request, $manager);

        if($result["fails"]){
//            dd( $result);
            return redirect()->route("admin.managers.edit", ["manager" => $manager->id])->withInput($request->all())->withErrors($result["errors"]);
        }
        $this->repository->updateManager($manager, $request);

        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Manager Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.managers.index");
    }

    public function destroy(Request $request, $manager){
//        dd($request->manager);
        $this->permissionsAllowed("admin-control");

        if(!hasPermissions("admin-control"))
            abort("401");
         Admin::findOrFail($request->manager)->delete();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Manager Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.managers.index");

    }

    public function exportExcelFile(Request $request){
        $headings = ["Name", "Username", "Email", "Email verified at", "Role", "Created at", "Updated at"];

        $managers = $this->repository->getAllManagers();

        $data = [];

        if($managers->isNotEmpty()) {
            foreach ($managers as $index => $manager) {
                $data[$index]["full_name"] = $manager->full_name;
                $data[$index]["username"] = $manager->username;
                $data[$index]["email"] = $manager->email;
                $data[$index]["email_verified_at"] = !empty($manager->email_verified_at) ? "Yes" : "No";
                $data[$index]["role"] = $manager->role->name;
                $data[$index]["created_at"] = $manager->created_at->diffForHumans();
                $data[$index]["updated_at"] = $manager->updated_at->diffForHumans();
            }
            return Excel::download(new ExeclExport($data, $headings , []), "managers.xlsx");
        }else
            return redirect()->back();

    }


    public function SendEmail(Request $request){
        $id =$request->id;
        $manager=Admin::find($id);

        $arr = [ 'name' => $manager->full_name  ,'url'=>"/en/admin/email/verification",'username' => $manager->username  ,'Password' => $manager->first_password ,'email' => $manager->email ];
        $manager->notify(new Registered($arr));
        $message = (new SuccessMessage())->title("Successfully")
            ->body("Email has been sent in Manager Successfully  ". $manager->email);
        Dialog::flashing($message);
        return redirect()->route("admin.managers.index");
    }

    public function VerificationEmail(){
        $admin =Auth::user();
        $admin->email_verified_at = now();
        $adminnew = $admin->save();
        return redirect()->route("admin.dashboard.index");

    }

    public function Test(){

        return view("admin.managers.test");
    }
}
