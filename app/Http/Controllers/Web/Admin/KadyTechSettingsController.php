<?php

namespace App\Http\Controllers\Web\Admin;
use App\Http\Controllers\Controller;
use App\Models\GeneralSettings;
use App\Models\KadyTechSetting;
use App\Repositories\KadyTechSettingsRepository;
use Illuminate\Http\Request;

class KadyTechSettingsController extends Controller
{
    protected KadyTechSettingsRepository $repository;

    public function __construct(KadyTechSettingsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request){
        $this->permissionsAllowed("control-settings");

        $data["general"] = GeneralSettings::where('type','kadytech')->first();
        return view("admin.KadyTech.settings", $data);
    }

    public function save(Request $request){
        $this->permissionsAllowed("control-settings");
        $this->repository->save($request);
        return redirect()->route("admin.KadyTech.settings.index");
    }
}
