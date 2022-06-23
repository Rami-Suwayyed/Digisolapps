<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\AdminRole;
use App\Models\Role;
use App\Repositories\RolePermissionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public RolePermissionRepository $repository;
    public function __construct(RolePermissionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(){
        $this->permissionsAllowed("admin-control");

        $data["roles"] = $this->repository->getAllRoles();
        return view("admin.roles.index", $data);
    }

    public function create(){
        $this->permissionsAllowed("admin-control");

        $data["permissions"] = $this->repository->getAllPermissions();
        return view("admin.roles.create", $data);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->permissionsAllowed("admin-control");

        $result = $this->repository->createValidation($request);
        if($result["fails"])
            return redirect()->route("admin.roles.create")->withInput($request->all())->withErrors($result["errors"]);
        $this->repository->createRole($request);

        $message = (new SuccessMessage())->title("Created Successfully")
            ->body("The Role Has Been Created Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.roles.index");
    }

    public function edit(Request $request){
        $this->permissionsAllowed("admin-control");

        $permissionsSelected = [];
        $data["role"] = $role = $this->repository->getRoleById($request->role);
        $data["permissions"] = $this->repository->getAllPermissions();
        $data["permissionsSelected"] = $this->repository->getPermissionsSelectedArray($request, $role);
        unset($role);
        return view("admin.roles.edit", $data);
    }

    public function update (Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->permissionsAllowed("admin-control");

        $role = $this->repository->getRoleById($request->role);
        $result = $this->repository->updateValidation($request, $role);
        if($result["fails"])
            return redirect()->route("admin.roles.edit", ["role" => $role->id])->withInput($request->all())->withErrors($result["errors"]);

        $this->repository->saveRole($role, $request->only(["name_en", "name_ar", "permissions"]));

        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Role Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.roles.index");
    }

    public function destroy(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->permissionsAllowed("admin-control");
        $this->repository->deleteRole($this->repository->getRoleById($request->role));
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Role Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.roles.index");
    }
}
