<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Null_;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            "full_name" => $this->full_name,
            "email" => $this->email,
            "gender" => $this->genderName,
            "birthday_date" => $this->birth_date,
            "last_update" => $this->updated_at->diffForHumans(),
            "profile_photo" => $this->getFirstMediaFile("profile_photo") ? $this->getFirstMediaFile("profile_photo")->url : null,
        ];
        if($this->type === 't'){
            $data["username"] = $this->teacher->username;
            $data["phone_number_1"] = $this->teacher->phone_number_1;
            $data["phone_number_2"] = $this->teacher->phone_number_2;
            $data["address"] = $this->teacher->address;
            $data["experience_years"] = $this->teacher->experience_years;
            $data["application_commission"] = $this->teacher->application_commission;

            switch ($this->teacher->category_type){
                case 3: $data["classification"] = __("Bronze"); break;
                case 2: $data["classification"] = __("Silver"); break;
                case 1: $data["classification"] = __("Gold"); break;
            }
        }else{
            $data["phone_number"] = $this->student->phone_number;
            $data['sharing_code'] = $this->code->code;
            $data['is_sharing'] = $this->code->user == Null ? 0 : 1;
        }
        return $data;
    }
}
