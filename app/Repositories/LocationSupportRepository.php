<?php

namespace App\Repositories;

use App\Models\LocationSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LocationSupportRepository
{
    public function getAllLocations(){
        return LocationSupport::all();
    }

    public function getLocationById($id){
        return LocationSupport::findOrFail($id);
    }

    public function buildTreeData($locations): array
    {
        $treeData = [];
        $parents = [];
        foreach($locations as $location){
            $full_location =  $location->country .
                (empty($location->governorate)  ?null: "-"  . $location->governorate) .
                (empty($location->locality)     ?null: "-"  . $location->locality) .
                (empty($location->sub_locality) ?null: "-"  . $location->sub_locality) .
                (empty($location->neighborhood) ?null: "-"  . $location->neighborhood);
            $parents[$full_location] = $location->id;
        }

        foreach($locations as $location){
            $level = null;
            $parent_location = null;
            if($location->country && !$location->governorate){
                $level = 1;
                $location->location_text = $location->country;
            }else if($location->country && $location->governorate && !$location->locality){
                $level = 2;
                $parent_location = $location->country;
                $location->location_text = $location->governorate;
            }else if($location->country && $location->governorate && $location->locality && !$location->sub_locality && !$location->neighborhood){
                $level = 3;
                $parent_location = $location->country . "-"  . $location->governorate;
                $location->location_text = $location->locality;
            }else if($location->country && $location->governorate && $location->locality && $location->sub_locality && !$location->neighborhood){
                $level = 4;
                $parent_location = $location->country . "-"  . $location->governorate . "-"  . $location->locality;
                $location->location_text = $location->sub_locality;

            }else if($location->country && $location->governorate && $location->locality && $location->sub_locality && $location->neighborhood){
                $level = 5;
                $parent_location = $location->country . "-"  . $location->governorate . "-"  . $location->locality .  "-"  . $location->sub_locality;
                $location->location_text = $location->neighborhood;
            }

            $treeData[$level][$parents[$parent_location] ?? 0][] = $location;
        }
        unset($parents);
        return $treeData;
    }

    public function checkingLocation(Request $request){
        $country = $request->country;
        $governorate = $request->governorate;
        $locality = $request->locality;
        $subLocality = $request->subLocality ?? null;
        $neighborhood = $request->neighborhood ?? null;
        $where = [
            ["country"      , "=", $country],
            ["governorate"  , "=", null],
            ["locality"     , "=", null],
            ["sub_locality" , "=", null],
            ["neighborhood" , "=", null]
        ];
        $sessionData = [
            "country" => null,
            "governorate" => null,
            "locality" => null,
            "sub_locality" => null,
            "neighborhood" => null,
        ];
        $responseData = [];
        $statusNumber = 'S300'; //300 already exits , 200 success , 400 not supported

        $countryResult = $this->getLocationWhere($where);

        if(!$countryResult){
            $responseData[] = $sessionData["country"] = $country;
        }else{
            $where = [
                ["country" , "=", $country],
                ["governorate" , "=", $governorate],
                ["locality"     , "=", null],
                ["sub_locality" , "=", null],
                ["neighborhood" , "=", null]
            ];
            $governorateResult = $this->getLocationWhere($where);

            if(!$governorateResult && $countryResult->support == 1){
                $responseData[] = $sessionData["country"] = $country;
                $responseData[] = $sessionData["governorate"] = $governorate;
            }else if($countryResult->support == 0){
                $statusNumber = 'S400';
            }else{
                $filterd_governorate = str_replace(" governorate","",strtolower($governorate));
                $filterd_locality = strtolower($locality);
                $where = [
                    ["country" , "=", $country],
                    ["governorate" , "=", $governorate],
                    ["locality" , "=", $locality],
                    ["sub_locality" , "=", null],
                    ["neighborhood" , "=", null]
                ];
                $localityResult = $this->getLocationWhere($where);

                if($filterd_governorate == $filterd_locality){
                    if(!$localityResult && $governorateResult->support == 1){
                        $localityResult = $this->createLocation($request->only(["country", "governorate", "locality"], $governorateResult->support));
                    }
                }
                if(!$localityResult && $governorateResult->support == 1){
                    $responseData[] = $sessionData["country"] = $country;
                    $responseData[] = $sessionData["governorate"] = $governorate;
                    $responseData[] = $sessionData["locality"] = $locality;
                }else if($governorateResult->support == 0){
                    $statusNumber = 'S400';
                }else{
                    $where = [
                        ["country" , "=", $country],
                        ["governorate" , "=", $governorate],
                        ["locality" , "=", $locality],
                        ["sub_locality" , "=", $subLocality],
                        ["neighborhood" , "=", null]
                    ];
                    $subLocalityResult = $this->getLocationWhere($where);

                    if(!$subLocalityResult && $subLocality !== null && $localityResult->support == 1){
                        $responseData[] = $sessionData["country"] = $country;
                        $responseData[] = $sessionData["governorate"] = $governorate;
                        $responseData[] = $sessionData["locality"] = $locality;
                        $responseData[] = $sessionData["sub_locality"] = $subLocality;
                    }else if($localityResult->support == 0){
                        $statusNumber = 'S400';
                    }else{
                        $where = [
                            ["country" , "=", $country],
                            ["governorate" , "=", $governorate],
                            ["locality" , "=", $locality],
                            ["sub_locality" , "=", $subLocality],
                            ["neighborhood" , "=", $neighborhood]
                        ];
                        $neighborhoodResult = $this->getLocationWhere($where);

                        if(!$neighborhoodResult && $neighborhood !== null && $subLocalityResult->support == 1){
                            $responseData[] = $sessionData["country"] = $country;
                            $responseData[] = $sessionData["governorate"] = $governorate;
                            $responseData[] = $sessionData["locality"] = $locality;
                            if($subLocality !== null)
                                $responseData[] = $sessionData["sub_locality"] = $subLocality;

                            $responseData[] = $sessionData["neighborhood"] =$neighborhood;
                        }else if($subLocality !== null && $subLocalityResult->support == 0){
                            $statusNumber = 'S400';
                        }
                    }
                }
            }
        }

        if($responseData){
            $statusNumber = 'S200';
            Session::put("location_details", $sessionData);
        }
        return ["data" => $responseData, "statusNumber" => $statusNumber];
    }

    public function getLocationWhere(array $where){
        return LocationSupport::where($where)->first();
    }

    public function createLocation($data, $support = true): LocationSupport
    {
        $location = new LocationSupport();
        $location->country = $data["country"];
        $location->governorate = $data["governorate"] ?? null;
        $location->locality = $data["locality"] ?? null;
        $location->sub_locality = $data["sub_locality"] ?? null;
        $location->neighborhood = $data["neighborhood"] ?? null;
        $this->saveLocation($location, $support);
        return $location;
    }

    public function saveLocation($location, $support){
        $location->support = $support ?? $location->support;
        $location->save();
    }

    public function updateLocation($location, $support){
        $support = (int)$support;
        if($support != $location->support){
            $sql = "UPDATE locations_support SET `support` = " . $support . " WHERE ";
            if($location->neighborhood){
                $sql .= "country = '" . $location->country . "' AND governorate = '" . $location->governorate .
                    "' AND locality = '" . $location->locality . "' AND sub_locality = '" . $location->sub_locality .
                    "' AND neighborhood = '" . $location->neighborhood . "'";
            }else{
                if($location->sub_locality){
                    $sql .= "country = '" . $location->country . "' AND governorate = '" . $location->governorate .
                        "' AND locality = '" . $location->locality . "' AND sub_locality = '" . $location->sub_locality . "'";
                }else{
                    if($location->locality){
                        $sql .= "country = '" . $location->country . "' AND governorate = '" . $location->governorate .
                            "' AND locality = '" . $location->locality . "'";
                    }else{
                        if($location->governorate){
                            $sql .= "country = '" . $location->country . "' AND governorate = '" . $location->governorate . "'";
                        }else{
                            $sql .= "country = '" . $location->country . "'";
                        }
                    }
                }
            }
            DB::update($sql);
        }
    }

    public function deleteLocation($location){
        $sql = "DELETE FROM locations_support WHERE ";
        if($location->neighborhood){
            $sql .= "country = '" . $location->country . "' AND governorate = '" . $location->governorate .
                "' AND locality = '" . $location->locality . "' AND sub_locality = '" . $location->sub_locality .
                "' AND neighborhood = '" . $location->neighborhood . "'";
        }else{
            if($location->sub_locality){
                $sql .= "country = '" . $location->country . "' AND governorate = '" . $location->governorate .
                    "' AND locality = '" . $location->locality . "' AND sub_locality = '" . $location->sub_locality . "'";
            }else{
                if($location->locality){
                    $sql .= "country = '" . $location->country . "' AND governorate = '" . $location->governorate .
                        "' AND locality = '" . $location->locality . "'";
                }else{
                    if($location->governorate){
                        $sql .= "country = '" . $location->country . "' AND governorate = '" . $location->governorate . "'";
                    }else{
                        $sql .= "country = '" . $location->country . "'";
                    }
                }
            }
        }
        DB::delete($sql);
    }
}
