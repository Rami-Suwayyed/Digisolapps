<?php

namespace App\Http\Resources\UsersProfiles;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkerResource extends JsonResource
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
            "daily_price_from" => $this->daily_price_from,
            "daily_price_to" => $this->daily_price_to,
            "monthly_price_from" => $this->monthly_price_from,
            "monthly_price_to" => $this->monthly_price_to,
            "productivity_from" => $this->productivity_from,
            "productivity_to" => $this->productivity_to,
            "experience" => $this->experience ? $this->experience->id : null,
            "with_maintenance" => (bool) $this->with_maintenance,
            "productivity_unit" => $this->productivityUnit ?
                [ "id" => $this->productivityUnit->id,"name" => $this->productivityUnit->name] : null,

        ];
    }
}
