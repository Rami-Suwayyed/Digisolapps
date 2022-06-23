<?php

namespace App\Http\Resources\UsersProfiles;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractorResource extends JsonResource
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
            "normal_meter_from_price" => $this->normal_meter_from_price,
            "geometric_meter_from_price" => $this->geometric_meter_from_price,
            "normal_meter_to_price" => $this->normal_meter_to_price,
            "geometric_meter_to_price" => $this->geometric_meter_to_price,

            "experience" => $this->experience ? $this->experience->id : null,
            "with_maintenance" => (bool)$this->with_maintenance,
            "unit_normal_meter" => $this->unitNormalMeter ?
                [ "id" => $this->unitNormalMeter->id,"name" => $this->unitNormalMeter->name] : null,
            "unit_geometric_meter" => $this->unitGeometricMeter ?
                [ "id" => $this->unitGeometricMeter->id,"name" => $this->unitGeometricMeter->name] : null,
            "classification" => $this->classficationCategory ? [
                "scope_id" => $this->classficationCategory->major->scope->id,
                "major_id" => $this->classficationCategory->major->id,
                "category_id" => $this->classficationCategory->id,
            ] : []
        ];
    }
}
