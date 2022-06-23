<?php

namespace App\Repositories;

use App\Models\Slider;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class SliderRepository
{
   public function  getAll(){
    return Slider::all();
   }
   public function findById($id)
   {
       return Slider::findOrFail($id);
   }

    public function rules(){
        return [
            "slider_image" => ["required", "image"]
        ];
    }

    public function validation(Request $request): array
    {
        $result["fails"] = false;
        $valid = Validator::make($request->all(), $this->rules());
        if ($valid->fails()){
            $result["fails"] = true;
            $result["errors"] = $valid->errors()->messages();
        }
        return $result;
    }

    public function createSlider(Request $request){
        $slider = new Slider();
        $this->saveSlider($slider, $request->file("slider_image"));
    }

    public function updateSlider($sliderId, Request $request){
        $slider = Slider::findOrFail($sliderId);
        $this->saveSlider($slider, $request->file("image"));
    }

    public function saveSlider(Slider &$slider, UploadedFile $image){
        $slider->save();
        if($slider->getFirstMediaFile())
            $slider->removeAllFiles();
        $slider->saveMedia($image);
    }

    public function delete($slider){
       $slider->removeAllFiles();
       $slider->delete();
    }
}
