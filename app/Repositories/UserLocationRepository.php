<?php

namespace App\Repositories;

use App\Models\UserLocation;

class UserLocationRepository
{

    public function rules(): array
    {
        return [
            "title" => ["required"],
            "lat" => ["required", "numeric"],
            "lng" => ["required", "numeric"],
            "apartment_number" => ["required", "max:255"],
            "region" => ["required", "max:255"],
            "street" => ["max:255"],
            "description" => ["max:1000"]
        ];
    }

    public function createLocation($request, $user){
        $location = new UserLocation();
        $location->title = $request->title;
        $location->lat = $request->lat;
        $location->lng = $request->lng;
        $location->apartment_number = $request->apartment_number;
//        $location->building_name = $request->building_name;
        $location->building_name = "Deflut";
        $location->region = $request->region;
        $location->street = $request->street ?? null;
        $location->description = $request->description ?? null;
        $user->locations()->save($location);
    }

    public function updateLocation($request){
        $location = UserLocation::findOrFail($request->id);
        $location->title = $request->title;
        $location->lat = $request->lat;
        $location->lng = $request->lng;
        $location->apartment_number = $request->apartment_number;
        $location->building_name = $request->building_name;
        $location->region = $request->region;
        $location->street = $request->street ?? null;
        $location->description = $request->description ?? null;
        $location->save();
    }

    public function getUserLocations($userId){
       return UserLocation::selectBuilder()->byUserId($userId)->get();
    }

}
