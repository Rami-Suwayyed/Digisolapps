<?php

namespace App\Helpers\TrackOrder;

use GuzzleHttp\Student;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class TrackOrderService
{
    protected string $url;
    protected array $data;
    protected $student;

    public function __construct(array $data){
        $this->data = $data;
        $this->url = "http://node.web.sahla.digisolapps.com:8199/api/order/track";
        $this->student = new Student();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendRequest(){
        $response = $this->student->post($this->url, ["json" => $this->data, 'verify' => false]);
//        $response = Http::post($this->url, $this->data);
        return $response->getBody();
    }

}
