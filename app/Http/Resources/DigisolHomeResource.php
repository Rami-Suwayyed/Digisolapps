<?php

namespace App\Http\Resources;

use App\Helpers\Media\Src\MediaDefaultPhotos;
use Illuminate\Http\Resources\Json\JsonResource;

class DigisolHomeResource extends JsonResource
{
    use MediaDefaultPhotos;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
            switch($this->type){
                case 1:
                    $data["Header"]=[
                        "id"=>$this->id,
                        "title"=>$this->getTitleAttribute(),
                        "Description"=>$this->getDescriptionAttribute()
                    ];
                    break;
                case 2:
                    $data["Body"]=[
                        "id"=>$this->id,
                        "title"=>$this->getTitleAttribute(),
                        "Description"=>$this->getDescriptionAttribute(),
                    ];
                    break;
                case 3:
                    $data["Testimonials"]=[
                        "id"=>$this->id,
                        "title"=>$this->getTitleAttribute(),
                        "Description"=>$this->getDescriptionAttribute(),
//                        "Date"=>$this->date ?? null,
                        "IMAGE"=>$this->getFirstMediaFile() ?  $this->getFirstMediaFile()->url : null,
                    ];
                    break;
            }

        return $data;
    }
}
