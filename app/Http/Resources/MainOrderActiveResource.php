<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class MainOrderActiveResource extends JsonResource
{
    use ResourcesHelper;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            "id" => $this->id,
            "user" => $this->user->full_name,
            "latitude"=>$this->order_location_latitude,
            "longitude"=>$this->order_location_longitude,
            "teacher"=>$this->userT->full_name,
        ];
    }
}
