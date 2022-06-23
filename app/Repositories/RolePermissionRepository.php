<?php

namespace App\Repositories;

use App\Models\AdminPermission;
use App\Models\AdminRole;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolePermissionRepository
{

    public function getAllPermissions(){
        return AdminPermission::all();
    }

    public function getAllRoles(){
        return AdminRole::all();
    }

    public function getRoleById($roleId){
        return AdminRole::findOrFail($roleId);
    }

    public function getPermissionsSelectedArray(Request $request, $role){
        $permissionsSelected = [];
        if($request->old("permissions"))
            foreach ($request->old("permissions") as $permission)
                $permissionsSelected[$permission] = true;
        else
            foreach ($role->permissions as $permission)
                $permissionsSelected[$permission->id] = true;
        return $permissionsSelected;
    }

    public function rules(): array
    {
        return [
            "name_en" => ["required", "unique:admin_roles","max:255"],
            "name_ar" => ["required", "unique:admin_roles","max:255"],
            "permissions" => ["required"],
            "permissions.*" => ["required"]
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

    public function updateValidation(Request $request, $role): array
    {
        $rules = $this->rules();
        if($request->name_en === $role->name_en)
            $rules["name_en"] = [];
        if($request->name_ar === $role->name_ar)
            $rules["name_ar"] = [];
        return $this->validation($request, $rules);
    }


    public function createRole(Request $request){
        $role = new AdminRole();
        $this->saveRole($role, $request->only(["name_en", "name_ar", "permissions"]));
    }

    public function saveRole(AdminRole &$role, array $data){
        $role->name_en = $data["name_en"];
        $role->name_ar = $data["name_ar"];
        $role->save();
        if($data["permissions"])
            $role->permissions()->sync($data["permissions"]);
    }

    public function deleteRole($role){
        $role->delete();
    }


}
