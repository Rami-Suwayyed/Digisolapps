<?php

namespace App\Helpers\Notification\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class NotificationNodeSender
{
    protected string $url;
    protected array $data;
    protected $client;

    public function __construct(array $data){
        $this->data = $data;
        $this->url = env("NODE_NOTIFICATION_URL");
        $this->client = new Client();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendRequest(){
        $response = $this->client->post($this->url, ["json" => $this->data, 'verify' => false]);
//        $response = Http::post($this->url, $this->data);
        return $response->getBody();
    }

}
