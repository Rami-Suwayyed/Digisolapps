<?php

namespace App\Repositories;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Models\PropertySelection;
use App\Models\PropertySwitch;
use App\Models\SubjectProperty;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectPropertyRepository
{

    public function rules(Request $request, $type){
        $rules = [
            "property_ar_title" => ["required"],
            "property_en_title" => ["required"],
        ];

        switch ($type){
            case "SL":
            case "DP":
            case "QS":
                $rules["option_en_title.*"] = ["required"];
                $rules["option_ar_title.*"] = ["required"];
                $rules["price.*"] = ["required"];
            break;
            case "SW":
                $rules["price"] = ["required"];
            break;

            default:
                throw new \Exception("not found type");
            break;
        }
        return $rules;

    }

    public function columns(Request $request, $type)
    {
        $customAttributesNames = [
            "property_en_title" => "Property English Title",
            "property_ar_title" => "Property Arabic Title",
        ];
        switch ($type){
            case "SL":
            case "DP":
            case "QS":
                $customAttributesNames["option_en_title.*"] = "option english title";
                $customAttributesNames["option_ar_title.*"] = "option arabic title";
                $customAttributesNames["price.*"] = "price";
            break;
            case "SW": break;

            default:
                throw new \Exception("not found type");
            break;
        }
        return $customAttributesNames;
    }

    public function createProperty(Request $request){
        $property = new SubjectProperty();
        $property->subject_id = $request->subject_id;
        $property->type = $request->type;
        $property->name_en = $request->property_en_title;
        $property->name_ar = $request->property_ar_title;
        if($property->save()) {
            switch ($request->type){
                case "SL":
                case "DP":
                case "QS":
                    $this->saveSelections($request, $property->id);
                break;
                case "SW":
                    $this->saveSwitch($request, $property->id);
                break;
                default:
                    throw new \Exception("not found type");
                break;
            }
        }
    }

    public function updateProperty(Request $request, $property){
        $property->name_en = $request->property_en_title;
        $property->name_ar = $request->property_ar_title;
        if($property->save()) {
            switch ($property->type){
                case "SL":
                case "DP":
                case "QS":
                    $this->updateSelections($request, $property, count($request->option_en_title));
                    break;
                case "SW":
                    $this->updateSwitch($request, $property);
                    break;

            }
        }
    }

    public function updateSwitch(Request $request, $property){
        $switch = $property->options;
        $switch->price = $request->price;
        $switch->save();
    }

    public function saveSwitch(Request $request, $property_id){
        $switch = new PropertySwitch();
        $switch->price = $request->price;
        $switch->property_id = $property_id;
        $switch->save();
    }

    public function saveSelections(Request $request, $property_id){
        for ($i = 0; $i < count($request->option_en_title); $i++){
            $selectionOption = new PropertySelection();
            $selectionOption->name_en = $request->option_en_title[$i];
            $selectionOption->name_ar = $request->option_ar_title[$i];
            $selectionOption->price = $request->price[$i];
            $selectionOption->property_id = $property_id;
            $selectionOption->save();
        }
    }

    public function updateSelections(Request $request, SubjectProperty $property, $numberOptions){
        $counter = 0;
        $selectionOptions = $property->options;
        foreach ($selectionOptions as $selectionOption){
            $selectionOption->name_en = $request->option_en_title[$counter];
            $selectionOption->name_ar = $request->option_ar_title[$counter];
            $selectionOption->price = $request->price[$counter];
            $selectionOption->save();
            $counter++;
        }
        for ($i = $counter; $i < $numberOptions; $i++){
            $selectionOption = new PropertySelection();
            $selectionOption->name_en = $request->option_en_title[$i];
            $selectionOption->name_ar = $request->option_ar_title[$i];
            $selectionOption->price = $request->price[$i];
            $selectionOption->property_id = $property->id;
            $selectionOption->save();
        }
    }

}
