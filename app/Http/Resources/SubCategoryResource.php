<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class SubCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $lang = App::getLocale();

        return [
            "id" => $this->id,
            "name" => $this->{"name_" . $lang},
            "image_url" => $this->getFirstMediaFile() ? $this->getFirstMediaFile()->url : null,
        ];
    }
}
