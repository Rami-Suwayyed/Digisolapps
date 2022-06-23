<?php

namespace App\Http\Resources;

use App\Models\MainCategory;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class TeacherSubCategoryResource extends JsonResource
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

       $data['mainCategory']= [
          "mainCategory-id" =>$this->id,
          "mainCategory-name" =>$this->getNameAttribute(),
          "mainCategory-image_url" =>$this->getFirstMediaFile() ? $this->getFirstMediaFile()->url : null,
          ];
       foreach ($this->subCategories as $key=> $sub){
       $data['Sub_category'][$key] = [
              "Sub_category-id" => $sub->id,
              "Sub_category- name" => $this->getNameAttribute().'-'.$sub->getNameAttribute(),
              "Sub_category-image_url" => $sub->getFirstMediaFile() ? $sub->getFirstMediaFile()->url : null
           ];
       }

       return $data;
    }
    function gg($main){
        $data[] = MainCategory::where('id',$main)->get();

    }
}
