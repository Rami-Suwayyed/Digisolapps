<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSubjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $subject = $this->subject;
        $data = [
            "id"        => $this->subject->id,
            "name"      => $this->subject->full_name,
            "image"     => $this->subject->getFirstMediaFile() ?  $this->subject->getFirstMediaFile()->url : null
        ];

        if($this->price_type === "u"){
            $data["price_type"] = "unknown";
        }else if($this->price_type === "r"){
            $data["price_type"] = "range";
            $data["price"] = [
                "from" => $this->price_from,
                "to" => $this->price_to,
            ];
        }else if($this->price_type === "f"){
            $data["price_type"] = "fixed";
            $data["price"] = $this->price;
        }

        return $data;
    }
}
