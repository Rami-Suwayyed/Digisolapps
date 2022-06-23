<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Repositories\SliderRepository;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    protected SliderRepository $repository;
    public function __construct(SliderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $this->permissionsAllowed( "control-and-view-sliders");

        $data["sliders"] = $this->repository->getAll();
        return view("admin.slider.index", $data);
    }

    public function create(){
        $this->permissionsAllowed( "control-and-view-sliders");
        return view("admin.slider.create");
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->permissionsAllowed( "control-and-view-sliders");
        $validation = $this->repository->validation($request);
        if($validation["fails"]){
            return redirect()->route("admin.sliders.create")->withInput($request->all())->withErrors($validation["errors"]);
        }
        $this->repository->createSlider($request);

        $message = (new SuccessMessage())->title("Created Successfully")
            ->body("The Slider Been Created Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.sliders.index");
    }

    public function edit(Request $request){
        $this->permissionsAllowed( "control-and-view-sliders");
        $data["slider"] = $this->repository->findById($request->slider);
        return view("admin.slider.edit", $data);
    }

    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->permissionsAllowed( "control-and-view-sliders");
        $slider = $this->repository->findById($request->slider);
        $validation = $this->repository->validation($request);
        if($validation["fails"]){
            return redirect()->route("admin.sliders.edit", ["slider" => $slider->id])->withInput($request->all())->withErrors($validation["errors"]);
        }
        $this->repository->saveSlider($slider, $request->file("slider_image"));
        return redirect()->route("admin.sliders.index");
    }

    public function destroy(Request $request){
        $this->permissionsAllowed( "control-and-view-sliders");
        $this->repository->delete($this->repository->findById($request->slider));
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Slider Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.sliders.index");
    }
}
