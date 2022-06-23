<?php

namespace App\Packeges\GoogleMaps;

use Illuminate\Support\Facades\Http;
use Nette\Utils\Json;

class Request
{

    public string $url;
    public string $requestType;
    public array $requestData;

    /**
     * @param $url
     * @param $requestData
     */
    public function __construct(string $url, string $requestType = "POST", array $requestData = [])
    {
        $this->url = $url;
        $this->requestType = $requestType;
        $this->requestData = $requestData;
    }

    /**
     * @throws \Nette\Utils\JsonException
     */
    public function send(){
        if($this->requestType == "POST"){
            $result = Http::post($this->url, $this->requestData);
        }else{
            $result = Http::get($this->url, $this->requestData);
        }
        return json_decode($result, true);
    }


}
