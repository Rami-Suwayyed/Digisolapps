<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class SubjectTeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $data['Sub_category']= [
            "Sub_category-id" =>$this->id,
            "Sub_category-name" =>$this->getNameAttribute(),
//            "Sub_category-image_url" =>$this->getFirstMediaFile() ? $this->getFirstMediaFile()->url : null,
        ];
        foreach ($this->subjects as $key=> $subject){
            $data['subjects'][$key] = [
                "subject-id" => $subject->id,
                "subject- name" => $this->getNameAttribute().'-'.$subject->getNameAttribute(),
                "subject-image_url" => $subject->getFirstMediaFile() ? $subject->getFirstMediaFile()->url : null
            ];
        }

        return $data;
    }
}
