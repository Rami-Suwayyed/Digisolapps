<?php

namespace App\Helpers\Notifications\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class NotificationSender
{
    protected string $url;
    protected array $data;

    public function __construct(array $data){
        $this->data = $data;
        $this->url = env("FIREBASE_API_KEY");
        $this->ch = curl_init();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendRequest(){
        $headers = [
            'Authorization: key=' . $this->url,
            'Content-Type: application/json',
        ];

//        dd($headers);

        $jsonData = json_encode($this->data);

        curl_setopt($this->ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $jsonData);
        $response = curl_exec($this->ch);
        return $response;
    }

}
