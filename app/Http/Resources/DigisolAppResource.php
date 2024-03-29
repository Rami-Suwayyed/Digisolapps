<?php

namespace App\Http\Resources;

use App\Helpers\Media\Src\MediaDefaultPhotos;
use Illuminate\Http\Resources\Json\JsonResource;

class DigisolAppResource extends JsonResource
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

        $Web=$this->defaultWebPhoto();
        $Android=$this->defaultAndroidPhoto();
        $Ios=$this->defaultIosPhoto();
        $Huawei=$this->defaultHuaweiPhoto();

        // foreach ($Apps as $index=> $App) {
        //     $data[$index]['id'] = $App->id;
        //     $data[$index]['name'] = $App->getNameAttribute();
        //     $data[$index]['Description'] = $App->getDescriptionAttribute();
        //     $data[$index]['image-icon'] =  $App->getFirstMediaFile("icon")->url ?? null;
        //     $data[$index]['image-phone'] =  $App->getFirstMediaFile("phone")->url ?? null;
        //     $data[$index]['image-background'] =  $App->getFirstMediaFile("background")->url ?? null;
        // }
        $data = [
            "id"                   => $this->id,
            "Category"                 => $this->Category->getNameAttribute(),
            "name"                 => $this->getNameAttribute(),
            "Description"          => $this->getDescriptionAttribute(),
            "imageIcon"            => $this->getFirstMediaFile("icon") ? $this->getFirstMediaFile("icon")->url : null,
            "imagePhone"          => $this->getFirstMediaFile("phone") ?  $this->getFirstMediaFile("phone")->url : null,
            "imageBackground"     => $this->getFirstMediaFile("background") ?  $this->getFirstMediaFile("background")->url : null,
            "linkWeb" => [
                "link" => $this->link_web,
                "icon" => $Web,
            ],
            "linkAndroid" => [
                "link" => $this->link_android,
                "icon" => $Android,
            ],
            "linkIos" => [
                "link" => $this->link_ios,
                "icon" => $Ios,
            ],
            "linkHuawei" => [
                "link" => $this->link_huawei,
                "icon" => $Huawei,
            ],
        ];

        // if($this->link_web != null){
        //     $data["link_web"] = "unknown";
        // }else{
        //     $data["link_web"] = $this->link_web;
        //     $data["link"] = "dfdf";
        //  }

        return $data;
    }
}
