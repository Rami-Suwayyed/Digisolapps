<?php

namespace App\Http\Resources;

use App\Models\Mechanism;
use Illuminate\Http\Resources\Json\JsonResource;

class MechanismResource extends JsonResource
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
            "id" => $this->id,
            "name" => $this->name,
            "made_year" => $this->made_year,
            "daily_price_from" => $this->daily_price_from,
            "daily_price_to" => $this->daily_price_to,
            "contracting_price_from" => $this->contracting_price_from,
            "contracting_price_to" => $this->contracting_price_to,
            "company_name" => $this->company_made_name,
            "image" => $this->category->getFirstMediaFile() ? $this->category->getFirstMediaFile()->url : null,
            "country_made" => [
                "id" => $this->country->id,
                "name" => $this->country->name,
            ],
            "contracting_unit" => [
                "id" => $this->contractingUnit->id,
                "name" => $this->contractingUnit->name,
            ],
            "created_at" => $this->updated_at->diffForHumans(),
            "last_update" => $this->created_at->diffForHumans()
        ];
    }
}
