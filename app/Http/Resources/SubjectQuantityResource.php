<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectQuantityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data["from"] = $this->from;
        $data["to"] = $this->to == -1 ? "infinity" : $this->to;
        $data["price"] = $this->subject->price_type == "f" ?
            SubjectQuantityFixedPriceResource::make($this->priceFixed):
            SubjectQuantityRangePriceResource::make($this->priceRange);
        return $data;

    }
}
