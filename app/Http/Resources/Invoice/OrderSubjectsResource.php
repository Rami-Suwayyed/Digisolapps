<?php

namespace App\Http\Resources\Invoice;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderSubjectsResource extends JsonResource
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
            "id" => $this->subject->id,
            "name" => $this->subject->name,
            "count" => $this->subject_count,
            "prices" => $this->subject->getPrices()
        ];
    }
}
