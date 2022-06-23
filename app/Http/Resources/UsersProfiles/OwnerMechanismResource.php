<?php

namespace App\Http\Resources\UsersProfiles;

use Illuminate\Http\Resources\Json\JsonResource;

class OwnerMechanismResource extends JsonResource
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
            "experience" => $this->experience ? $this->experience->id : null,
            "productivity_from" => $this->productivity_from,
            "productivity_to"   => $this->productivity_to,
            "productivity_unit" => $this->productivityUnit ? [  "id" => $this->productivityUnit->id,
                                                                "name" => $this->productivityUnit->name ] : null,
        ];
    }
}
