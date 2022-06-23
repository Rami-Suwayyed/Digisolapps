<?php

namespace App\Http\Resources\UsersProfiles;

use Illuminate\Http\Resources\Json\JsonResource;

class MaterialLaboratoryResource extends JsonResource
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
            "lab_name" => $this->lab_name,
            "owner_name" => $this->lab_owner_name,
            "experience" => $this->experience ? $this->experience->id : null,
            "classification" => $this->classficationCategory ? [
                "scope_id" => $this->classficationCategory->major->scope->id,
                "major_id" => $this->classficationCategory->major->id,
                "category_id" => $this->classficationCategory->id,
            ] : []
        ];
    }
}
