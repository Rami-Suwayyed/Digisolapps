<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\CategoryApps;
use App\Repositories\CategoryAppRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryAppsController extends Controller
{
    public $repository;
    public function __construct(CategoryAppRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function rules(){
        return [
            "name_en" => ["required","max:255"],
            "name_ar" => ["required","max:255"],
        ];
    }

    protected function columns(){
        return [
            "name_en" => "English category name",
            "name_ar" => "Arabic category name",
        ];
    }

    public function index(Request $request){
        $this->permissionsAllowed("view-control-categories-apps");

        $data['categories'] = CategoryApps::orderBy("order")->get();
        $view = "admin.category_apps.index";
        if($request->page == "sort")
            $view = "admin.category_apps.sort";
        return view($view, $data);
    }

    public function create(){
        $this->permissionsAllowed("view-control-categories-apps");

        return view("admin.category_apps.create");
    }

    public function store(Request $request){
        $this->permissionsAllowed("view-control-categories-apps");

        $valid = Validator::make($request->all(), $this->rules(), [], $this->columns());
        if($valid->fails()){
            return redirect()->route("admin.category_apps.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $lastCategory = CategoryApps::orderBy("order", "desc")->first();
        $category = new CategoryApps();
        $category->name_en = $request->name_en;
        $category->name_ar = $request->name_ar;
        $category->slug = Str::slug($request->name_en);
        $category->order = $lastCategory ? $lastCategory->order + 1 : 1;
        $category->save();

        $message = (new SuccessMessage())->title("Created Successfully")
            ->body("The Category Has Been Created Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.category_apps.index");
    }

    public function sort(Request $request){
        $this->permissionsAllowed("view-control-categories-apps");

        $order = 1;
        foreach ($request->category as $id){
            $category = CategoryApps::findOrFail($id);
            if($category->order != $order){
                $category->order = $order;
                $category->save();
            }
            $order++;
        }

        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Categories Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.category_apps.index", ["page" => "sort"]);
    }


    public function edit(Request $request){
        $this->permissionsAllowed("view-control-categories-apps");
        $data["category"] = CategoryApps::findOrFail($request->id);
        return view("admin.category_apps.edit",$data);
    }


    public function update(Request $request){
        $this->permissionsAllowed("view-control-categories-apps");

        $rules = $this->rules();
        $category = CategoryApps::findOrFail($request->id);
        if(!$request->hasFile("main_category_photo"))
            $rules["main_category_photo"] = [];

        $valid = Validator::make($request->all(), $rules, [], $this->columns());
        if($valid->fails()){
            return redirect()->route("admin.category_apps.edit", $request->id)->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $category->name_en = $request->name_en;
        $category->name_ar = $request->name_ar;
        $category->slug = Str::slug($request->name_en);
        $category->save();
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Category Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.category_apps.index");
    }



    public function destroy(Request $request){
        $this->permissionsAllowed("view-control-categories-apps");

        $this->repository->delete($this->repository->getById($request->id));
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Category Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.category_apps.index");
    }
}
