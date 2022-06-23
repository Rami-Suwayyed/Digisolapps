<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Session;

class ClassficiationMajorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $data = [
            "id"        => $this->id,
            "name"      => $this->name,
            "categories"    => ClassficiationCategoryResource::collection($this->categories)
        ];

        return $data;
    }
}
