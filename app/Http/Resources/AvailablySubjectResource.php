<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AvailablySubjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "days" => [
                "sunday" => (bool)$this->sunday,
                "monday" => (bool)$this->monday,
                "tuesday" => (bool)$this->tuesday,
                "wednesday" => (bool)$this->wednesday,
                "thursday" => (bool)$this->thursday,
                "friday" => (bool)$this->friday,
                "saturday" => (bool)$this->saturday
            ],
            "time" => [
                "start" => $this->start_time,
                "end" => $this->end_time,
                "slice" => $this->time_slice,
            ]
        ];
    }
}
