<?php

namespace App\Http\Resources\User\Order\DetailsParts;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherAccepted extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $teacher = $this->acceptOrder()->teacher;
        return [
            "teacher_id" => $teacher->id,
            "name" => $teacher->full_name,
            "profile_photo" => $teacher->getFirstMediaFile('profile_photo') ? $teacher->getFirstMediaFile('profile_photo')->url : null,
            "rating" => $teacher->rating->average("number_rating") == Null ? 0.0 : $teacher->rating->average("number_rating")
        ];
    }
}
