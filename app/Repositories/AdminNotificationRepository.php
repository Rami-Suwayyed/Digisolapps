<?php

namespace App\Repositories;

use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use stdClass;

class AdminNotificationRepository
{
    public function getById($id){
        return AdminNotification::findOrFail($id);
    }
    public function rules(): array
    {
        return [
            "is_open" => ["required", "in:1,0"]
        ];
    }

    public function validation(Request $request): array
    {
        $result = [];
        $result["fails"] = false;
        $rules = $this->rules();
        if(!$request->has("is_open"))
            unset($rules["is_open"]);

        $valid = Validator::make($request->all(), $rules);
        if($valid->fails()){
            $result["fails"] = true;
            $result["errors"] = $valid->errors()->messages();
            return $result;
        }
        return $result;
    }

    public function update(Request $request,$notification){
        $collect = new StdClass();
        if($request->has("is_open"))
            $collect->is_open = $request->is_open;
        if($request->has("url"))
            $collect->url = $request->url;
        $this->saveNotification($collect ,$notification);
    }

    public function saveNotification($collect, $notification){
        $notification->is_open = boolval($collect->is_open ?? $notification->is_open);
        $notification->url = $collect->url ?? $notification->url;
        $notification->save();
    }

}
