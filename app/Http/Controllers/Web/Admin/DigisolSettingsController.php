<?php

namespace App\Http\Controllers\Web\Admin;
use App\Http\Controllers\Controller;
use App\Models\AppUrl;
use App\Models\DigisolSetting;
use App\Models\GeneralSettings;
use App\Repositories\SettingsRepository;
use Illuminate\Http\Request;

class DigisolSettingsController extends Controller
{
    protected SettingsRepository $repository;

    public function __construct(SettingsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request){
        $this->permissionsAllowed("control-settings");

        $data["general"] = DigisolSetting::first();
        return view("admin.digisol.settings", $data);
    }

    public function save(Request $request){
        $this->permissionsAllowed("control-settings");
        $this->repository->save($request);
        return redirect()->route("admin.digisol.settings.index");
    }
}
