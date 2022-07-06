<?php

namespace App\Http\Resources;

use App\Helpers\Media\Src\MediaDefaultPhotos;
use Illuminate\Http\Resources\Json\JsonResource;

class DigisolAboutResource extends JsonResource
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
        $title=$this->getTitleAttribute();
        $Description=$this->getDescriptionAttribute();
       switch($this->type){
           case 1:
               $data["First"]=["title"=>$title,"Description"=>$Description];
               break;
           case 2:
               $data["Second"]=["title"=>$title,"Description"=>$Description];
               break;
           case 3:
               $data["Third"]=["title"=>$title,"Description"=>$Description];
               break;
           case 4:
               $data["Fourth"]=["title"=>$title,"Description"=>$Description];
               break;
       }

        return $data;
    }
}
