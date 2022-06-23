<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListTeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //bachelors:0 / master:1 / doctoral:2 / diploma:3
        $value="bachelors";
        switch ($this->higher_education){
        case 0:
            $value="bachelors";
            break;
        case 1:
            $value="master";
            break;
        case 2:
            $value="doctoral";
            break;
        case 2:
            $value="diploma";
            break;
        }
        $data = [
            "id"                =>  $this->id,
            "teacher_id"        =>$this->user->id,
            "name"              => $this->user->full_name,
//            "distance"        => $this->distance,
            "email"             => $this->email,
            "higher_education"  => $value,
            "rating"            => $this->user->rating->avg("number_rating"),
            "price"             => $this->volunteer ? $this->price : 0,
            "ues_price"         => $this->volunteer?? 0,
            "image"             => $this->user->getFirstMediaFile('profile_photo')->url ?? null
        ];
        return $data;
    }
}
