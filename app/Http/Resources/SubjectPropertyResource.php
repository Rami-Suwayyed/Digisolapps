<?php

namespace App\Http\Resources;

use App\Http\Resources\Properties\SelectionsResource;
use App\Http\Resources\Properties\SwitchResource;
use App\Http\Resources\Properties\TextFieldResource;
use App\Models\PropertyTypes\TextField;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class SubjectPropertyResource extends JsonResource
{
    protected $response;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $this->response = [
            "id" => $this->id,
            "type" => $this->type,
            "name" => $this->name,
        ];
        $this->getOptions();

        return $this->response;
    }

    public function getOptions(){
        switch ($this->type){
            case "SL":
            case "QS":
            case "DP":
                $this->response["options"] =  SelectionsResource::collection($this->options);
            break;
            case "SW":
                $this->response["options"] = SwitchResource::make($this->options);
            break;
        }
        return null;
    }
}
