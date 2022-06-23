<?php

namespace App\Packeges\GoogleMaps\Geocoding;

use App\Packeges\GoogleMaps\Geocoding\AddressComponent;
use App\Packeges\GoogleMaps\Request;

class Geocoding
{
    public Request $request;
    public array $coordinates;
    public AddressComponent $addressComponent;
    protected string $url = "https://maps.googleapis.com/maps/api/geocode";

    /**
     * @throws \Exception
     */
    public function __construct($latitude, $longitude, $dataRetrieve = "json")
    {
        $dataRetrieve = strtolower($dataRetrieve);
        $this->coordinates["latitude"] = $latitude;
        $this->coordinates["longitude"] = $longitude;
        $this->address = [  "country" => null,
                            "governorate" => null,
                            "locality" => null,
                            "sub_locality" => null,
                            "neighborhood" => null];
        if(!in_array($dataRetrieve, ["json", "xml"]))
            throw new \Exception("Data Retrieve is not support");
        $this->url .= "/" . $dataRetrieve . "?latlng=" . $this->coordinates['latitude'] . "," . $this->coordinates["longitude"] . "&key=" . env("GOOGLE_API_KEY");
        $this->request = new Request($this->url);
        $this->addressComponent = new AddressComponent();
    }

    /**
     * @throws \Nette\Utils\JsonException
     */
    public function call(){
        $results = $this->request->send();
        foreach($results["results"] as $result){
            foreach($result["types"] as $type){
                switch ($type) {
                    case 'country':
                        $this->addressComponent->country = $result["address_components"][0]["long_name"];
                        break;

                    case 'administrative_area_level_1':
                        $this->addressComponent->governorate = $result["address_components"][0]["long_name"];
                        break;

                    case 'locality':
                        $this->addressComponent->locality  = $result["address_components"][0]["long_name"];
                        break;
                    case 'sublocality':
                        $this->addressComponent->subLocality = $result["address_components"][0]["long_name"];
                        break;

                    case 'neighborhood':
                        $this->addressComponent->neighborhood = $result["address_components"][0]["long_name"];
                        break;
                }
            }
        }
    }
    /**
     * @throws \Nette\Utils\JsonException
     */
    public function getAddressComponents(): AddressComponent
    {
        return $this->addressComponent;
    }
}
