<?php

namespace App\Http\Controllers\Web\Admin;
use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\KadyTechSocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KadyTechSocialController extends Controller
{

    public function rules(){
        return [
            "url"                 =>["required", "url"],
            "type"                =>['required', "max:255"],
            "social_media_image"  =>['required', 'image']
        ];
    }

    public function index()
    {
        $data['socialMedias'] = KadyTechSocialMedia::all();
        return view("admin.KadyTech.social_media.index", $data);
    }


    public function create()
    {
        return view("admin.KadyTech.social_media.create");
    }


    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.KadyTech.social.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $socialMedia = new KadyTechSocialMedia();
        $socialMedia->url = $request->url;
        $socialMedia->type = $request->type;
        if($socialMedia->save()){
            if($request->hasFile("social_media_image")){
                $socialMedia->saveMedia($request->file("social_media_image"));
            }
        }
        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The Social Media Has Been Create Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.KadyTech.social.index");
    }


    public function edit(Request $request)
    {
        $data['socialMedia'] = KadyTechSocialMedia::find($request->id);
        return view("admin.KadyTech.social_media.edit", $data);
    }


    public function update(Request $request, $id)
    {
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            return redirect()->route("admin.KadyTech.social.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }
        $socialMedia = KadyTechSocialMedia::findOrFail($request->id);
        $socialMedia->url = $request->url;
        $socialMedia->type = $request->type;
        $socialMedia->save();
        if($request->hasFile("social_media_image")){
            $socialMedia->removeAllFiles();
            $socialMedia->saveMedia($request->file("social_media_image"));
        }
        $message = (new SuccessMessage())->title("Updated Successfully")
            ->body("The Social Media Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.KadyTech.social.index");
    }


    public function destroy(Request $request)
    {
        $socialMedia = KadyTechSocialMedia::find($request->id);
        if($socialMedia->delete()){
            $socialMedia->removeAllFiles();
        }
        $message = (new DangerMessage())->title("Deleted Successfully")
            ->body("The Social Media Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.KadyTech.social.index");
    }
}
