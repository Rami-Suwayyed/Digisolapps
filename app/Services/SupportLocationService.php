<?php

namespace App\Services;

use App\Models\LocationSupport;
use App\Packeges\GoogleMaps\Geocoding\Geocoding;

class SupportLocationService
{

    /**
     * @throws \Exception
     */
    public function checkIsSupport($latitude, $longitude): int
    {
        $geocoding = new Geocoding($latitude, $longitude);
        $geocoding->call();
        $addressComponent = $geocoding->getAddressComponents();
        $where = [
            "country" => $addressComponent->country,
            "governorate" => $addressComponent->governorate,
            "locality" => $addressComponent->locality,
            "sub_locality" => $addressComponent->subLocality,
            "neighborhood" => $addressComponent->neighborhood,
        ];
        $location = LocationSupport::where($where)->first();
        if(!$location){
            $where["neighborhood"] = null;
            $location = LocationSupport::where($where)->first();
            if(!$location) {
                $where["sub_locality"] = null;
                $location = LocationSupport::where($where)->first();
                if (!$location) {
                    $where["locality"] = null;
                    $location = LocationSupport::where($where)->first();
                    if (!$location) {
                        $where["sub_locality"] = null;
                        $location = LocationSupport::where($where)->first();
                        if (!$location) {
                            $where["governorate"] = null;
                            $location = LocationSupport::where($where)->first();
                            if (!$location) {
                                $where["neighborhood"] = null;
                                $location = LocationSupport::where($where)->first();
                            }
                        }
                    }
                }
            }
        }
        if(!$location)
            return 0;
        else
            return $location->support;
    }

}
