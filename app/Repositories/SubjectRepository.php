<?php

namespace App\Repositories;


use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Resources\SubjectPropertyResource;
use App\Http\Resources\SubjectQuantityResource;
use App\Http\Resources\SubjectQuestionResource;
use App\Http\Resources\SubjectResource;
use App\Models\AvailableDaysSubject;
use App\Models\AvailableTimesSubject;
use App\Models\MainCategory;
use App\Models\OrderSubject;
use App\Models\Subject;
use App\Models\SubjectQuantityFixedPrice;
use App\Models\SubjectQuantity;
use App\Models\SubjectQuantityRangePrice;
use App\Models\SubjectSettings;
use App\Rules\CheckQuantitySequenceIsValid;
use App\Rules\CheckIsSubCategoryInLastLevel;
use App\Rules\CheckIsValidTime;
use App\Rules\CheckSliceTimeIsValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubjectRepository
{
    public function getAllAvailable(){
        return Subject::available()->get();
    }

    public function getAllAvailableTeacher(){
        return Subject::available()->join("subjects_teachers", "subjects_teachers.subject_id", "=", "subjects.id")
            ->where("subjects_teachers.teacher_id", Auth::user()->teacher->id)->get("subjects.*");
    }



    public function showForApi($subjectId): array
    {
        $data = [];
        $subject = Subject::selectBuilder()->byId($subjectId)->available()->ordering()->firstOrFail();
        $data["id"] = $subject->id;
        $data["name"] = $subject->name;
        $data["price_type"] = $subject->price_type;
        $data["note"] = $subject->subject_note;
        $data["has_counter"] = $subject->settings->has_counter;
        $data["has_quantities"] = $subject->settings->has_quantities;
        $data["notes_required"] = $subject->settings->is_order_notes_required;
        $data["properties"] = SubjectPropertyResource::collection($subject->properties);
        $data["quantities"] = SubjectQuantityResource::collection($subject->quantities);
        $data["questions"] = SubjectQuestionResource::collection($subject->questions);
        return $data;
    }

    public function getSubjectsBySubCategory($subCategoryId): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $subject = Subject::selectBuilder()->bySubCategory($subCategoryId)->ordering()->get();
        return SubjectResource::collection($subject);
    }

    public function rules(Request $request): array
    {

        $rules = [
            "name_en"               => ["required","max:255"],
            "name_ar"               => ["required","max:255"],
            "subject_photo"         => ["image","max:512"],

        ];
        switch($request->price_type){
            case "f":
                if($request->has_quantity){
                    $rules["quantity_from"] = $rules["quantity_to"] = $rules["price"] = ["required"];
                    $rules["quantity_from.*"]  = $rules["quantity_to.*"] = $rules["price.*"] = $rules["quantity_from_infinity"] = $rules["price_infinity"] = ["required", "numeric"];
                    $rules["quantity_from.*"][] = "min:0";
                    $rules["quantity_to.*"][] = "gt:quantity_from.*";
                }else{
                    $rules["price"] =["required", "numeric"];
                }
            break;
            case "r":
                if($request->has_quantity){
                    $rules["quantity_from"] = $rules["quantity_to"] = $rules["price_from"] = $rules["price_to"] = ["required"];
                    $rules["quantity_from.*"]  = $rules["quantity_to.*"] = $rules["price_from.*"] = $rules["price_to.*"] = $rules["quantity_from_infinity"] = $rules["price_from_infinity"] = $rules["price_to_infinity"] = ["required", "numeric"];
                    $rules["quantity_from.*"][]= 'min:0';
                    $rules["quantity_to.*"][] = "gt:quantity_from.*";
                    $rules["price_to.*"][] = "gt:price_from.*";
                    $rules["price_to_infinity"] = "gt:price_from_infinity";
                }else{
                    $rules["price_from"] = $rules["price_to"] = ["required"];
                    $rules["price_to"] = "gt:price_from";
                }
            break;
        }

        return $rules;
    }

    public function columns(){
        return [
            "name_en" => "english subject name",
            "name_ar" => "arabic subject name",
            "price.*" => "price",
            "price_from.*" => "price from",
            "price_to.*" => "price to",
            "price_from_infinity" => "price from",
            "price_to_infinity" => "price to",
            "price_infinity" => "price",
            "quantity_from.*" => "quantity from",
            "quantity_to.*" => "quantity to",
            "quantity_from_infinity" => "quantity from",
        ];
    }

    public function checkIsQuantitiesIsValid(Request $request): array
    {
        $messages = [];
        $quantities = [];

        $quantities["from"] = $request->quantity_from;
        $quantities["to"] = $request->quantity_to;
        $validQuantity = 1;
        for($i = 0; $i < count($quantities["from"]); $i++){
            if($validQuantity != (int)$quantities["from"][$i]){
                $messages["quantity_from.$i"][] = __("The Quantity Must Be {$validQuantity}");
            }
            $validQuantity = $quantities["to"][$i] + 1;
        }

        if($validQuantity != $request->quantity_from_infinity){
            $messages["quantity_from_infinity"][] = __("The Quantity Must Be {$validQuantity}");
        }

        return ["fails" => !empty($messages), "errors" => $messages];
    }

    public function checkValidation(Request $request, $rules): array
    {
        $valid = Validator::make($request->all(), $rules, [], $this->columns());
        $result["fails"] = $valid->fails();
        $result["errors"] = $valid->errors()->messages();
        if($request->has_quantity && !$valid->fails() && $request->price_type !== 'u')
            $result = $this->checkIsQuantitiesIsValid($request);
        return $result;
    }

    public function createSubject(Request $request){
        $subject = Subject::selectBuilder()->byOrder("desc")->first();
        $order = $subject ? $subject->order + 1 : 1;
        unset($subject);
        $subject = new Subject();
        $subject->price_type = $request->price_type;
        $subject->order = $order;
        $subject->sub_category_id = $request->sub_id;
        $this->saveSubject($request, $subject);

    }

    public function saveSubject(Request $request, Subject &$subject){

        $subject->name_en = $request->name_en;
        $subject->name_ar = $request->name_ar;
        $subject->subject_note_en = $request->subject_note_en;
        $subject->subject_note_ar = $request->subject_note_ar;
        $subject->guarantee_days = 0;
        $subject->addition_commission = 0;
        $subject->ues_online = $request->ues_online ? $request->ues_online : 0;
        $subject->save();
        if($request->file("subject_photo"))
            $subject->saveMedia($request->file("subject_photo"));

        $subjectSettings = new SubjectSettings();
        $this->saveSubjectSettings($request, $subjectSettings, $subject);

        if($subject->price_type !== 'u')
            $this->saveQunatitiesOfSubjects($request, $subject->id);
        if($request->questions)
            $this->linkedQuestionsToSubject($request->questions, $subject);

    }

    public function updateSubject(Request $request, Subject $subject){
        $subject->name_en = $request->name_en;
        $subject->name_ar = $request->name_ar;
        $subject->subject_note_en = $request->subject_note_en;
        $subject->subject_note_ar = $request->subject_note_ar;
        $subject->guarantee_days = 0;
        $subject->addition_commission = 0;
        $subject->ues_online = $request->ues_online ? $request->ues_online : 0;
        $subject->save();
        if($request->hasFile("subject_photo")){
            if($subject->getFirstMediaFile())
                $subject->removeMedia($subject->getFirstMediaFile());;

            $subject->saveMedia($request->file("subject_photo"));
        }
        $this->saveSubjectSettings($request, $subject->settings, $subject);
        $this->linkedQuestionsToSubject($request->questions, $subject);

        if($request->price_type !== 'u')
            $this->updateQuantitiesOfSubjects($request, $subject);
        if($request->questions)
            $this->linkedQuestionsToSubject($request->questions, $subject);

    }

    public function linkedQuestionsToSubject($questions, Subject $subject){
        $subject->questions()->sync($questions);
    }

    public function saveQunatitiesOfSubjects(Request $request, $subjectId){
        if($request->has_quantity){
            switch ($request->price_type){
                case "f":
                    for($i = 0; $i < count($request->quantity_from); $i++) {
                        $quantity = new SubjectQuantity();
                        $this->saveQuantity($quantity, $request->quantity_from[$i], $request->quantity_to[$i], "fixed",$subjectId);
                        $fixedPrice = new SubjectQuantityFixedPrice();
                        $this->saveFixedPrice($fixedPrice, $request->price[$i], $quantity);
                    }
                    $quantity = new SubjectQuantity();
                    $this->saveQuantity($quantity, $request->quantity_from_infinity, -1, "fixed",$subjectId);
                    $fixedPrice = new SubjectQuantityFixedPrice();
                    $this->saveFixedPrice($fixedPrice, $request->price_infinity, $quantity);
                break;
                case "r":
                    for($i = 0; $i < count($request->quantity_from); $i++) {
                        $quantity = new SubjectQuantity();
                        $this->saveQuantity($quantity, $request->quantity_from[$i], $request->quantity_to[$i], "range", $subjectId);
                        $rangePrice = new SubjectQuantityRangePrice();
                        $this->saveRangePrice($rangePrice, $request->price_from[$i], $request->price_to[$i], $quantity);
                    }
                    $quantity = new SubjectQuantity();
                    $this->saveQuantity($quantity, $request->quantity_from_infinity, -1, "range", $subjectId);
                    $rangePrice = new SubjectQuantityRangePrice();
                    $this->saveRangePrice($rangePrice, $request->price_from_infinity, $request->price_to_infinity, $quantity);
                break;
            }

        }else{
            switch ($request->price_type){
                case "f":
                    $quantity = new SubjectQuantity();
                    $this->saveQuantity($quantity, 1, -1, "fixed",$subjectId);
                    $fixedPrice = new SubjectQuantityFixedPrice();
                    $this->saveFixedPrice($fixedPrice, $request->price, $quantity);
                    break;
                case "r":
                    $quantity = new SubjectQuantity();
                    $this->saveQuantity($quantity, 1, -1, "range", $subjectId);
                    $rangePrice = new SubjectQuantityRangePrice();
                    $this->saveRangePrice($rangePrice, $request->price_from, $request->price_to, $quantity);
                break;
            }
        }

    }

    public function updateQuantitiesOfSubjects(Request $request, Subject $subject){
        if($request->has_quantity){
            switch ($request->price_type){
                case "f":
                    $quantities = $subject->quantities()->orderBy('from', 'asc')->get();
                    $originQuantityLength = count($quantities) - 1; // To Skip Infinity Quantity
                    $updatedQuantityLength = count($request->quantity_from); // get All Quantity
                    $positionQuantity = 0;

                    //Update Origin
                    for($i = 0; $i < $originQuantityLength; $i++) {
                        $quantity = $quantities[$i];
                        $this->saveQuantity($quantity, $request->quantity_from[$i], $request->quantity_to[$i], "fixed", $subject->id);
                        $fixedPrice = $quantities[$i]->priceFixed;
                        $this->saveFixedPrice($fixedPrice, $request->price[$i], $quantity);
                        $positionQuantity++;
                    }

                    //Update From To Infinity
                    $quantity = $quantities[$positionQuantity];
                    $this->saveQuantity($quantity, $request->quantity_from_infinity, -1, "fixed",$subject->id);
                    $fixedPrice = $quantities[$positionQuantity]->priceFixed;
                    $this->saveFixedPrice($fixedPrice, $request->price_infinity, $quantity);

                    //add Addition
                    for($i = $positionQuantity; $i < $updatedQuantityLength; $i++) {
                        $quantity = new SubjectQuantity();
                        $this->saveQuantity($quantity, $request->quantity_from[$i], $request->quantity_to[$i], "fixed", $subject->id);
                        $fixedPrice = new SubjectQuantityFixedPrice();
                        $this->saveFixedPrice($fixedPrice, $request->price[$i], $quantity);
                    }

                break;
                case "r":
                    $quantities = $subject->quantities()->orderBy('from', 'asc')->get();
                    $originQuantityLength = count($quantities) - 1; // To Skip Infinity Quantity
                    $updatedQuantityLength = count($request->quantity_from); // get All Quantity
                    $positionQuantity = 0;

                    //Update Origin
                    for($i = 0; $i < $originQuantityLength; $i++) {
                        $quantity = $quantities[$i];
                        $this->saveQuantity($quantity, $request->quantity_from[$i], $request->quantity_to[$i], "range", $subject->id);
                        $rangePrice = $quantities[$i]->priceRange;
                        $this->saveRangePrice($rangePrice, $request->price_from[$i], $request->price_to[$i], $quantity);
                        $positionQuantity++;
                    }

                    //Update From To Infinity
                    $quantity = $quantities[$positionQuantity];
                    $this->saveQuantity($quantity, $request->quantity_from_infinity, -1, "range", $subject->id);
                    $rangePrice = $quantities[$positionQuantity]->priceRange;
                    $this->saveRangePrice($rangePrice, $request->price_from_infinity, $request->price_to_infinity, $quantity);

                    //add Addition
                    for($i = $positionQuantity; $i < $updatedQuantityLength; $i++) {
                        $quantity = new SubjectQuantity();
                        $this->saveQuantity($quantity, $request->quantity_from[$i], $request->quantity_to[$i], "range", $subject->id);
                        $rangePrice = new SubjectQuantityRangePrice();
                        $this->saveRangePrice($rangePrice, $request->price_from[$i], $request->price_to[$i], $quantity);
                    }

                break;
            }

        }else{
            switch ($request->price_type){
                case "f":
                    $quantity = $subject->quantities()->first();
                    $fixedPrice = $quantity->priceFixed;
                    $this->saveFixedPrice($fixedPrice, $request->price, $quantity);
                break;
                case "r":
                    $quantity = $subject->quantities()->first();
                    $rangePrice = $quantity->priceRange;
                    $this->saveRangePrice($rangePrice, $request->price_from, $request->price_to, $quantity);
                break;
            }
        }

    }

    public function saveQuantity(SubjectQuantity &$quantity, $quantityFrom, $quantityTo, $type, $subjectId){
        $quantity->from = $quantityFrom;
        $quantity->to = $quantityTo;
        $quantity->subject_id = $subjectId;
        $quantity->price_type = $type;
        $quantity->save();
    }

    public function saveFixedPrice(SubjectQuantityFixedPrice &$fixedPrice, $price, $quantity){
        $fixedPrice->price = $price;
        $fixedPrice->subject_quantity_id = $quantity->id;
        $fixedPrice->save();
    }

    public function saveRangePrice(SubjectQuantityRangePrice &$priceRange, $priceFrom, $priceTo, $quantity){
        $priceRange->from = $priceFrom;
        $priceRange->to = $priceTo;
        $priceRange->subject_quantity_id = $quantity->id;
        $priceRange->save();
    }

    public function saveSubjectSettings(Request $request, SubjectSettings $subjectSettings, Subject &$subject){
        $subjectSettings->has_counter = isset($request->has_counter);
        $subjectSettings->has_quantities = isset($request->has_quantity);
        $subjectSettings->is_order_notes_required = isset($request->notes_required);
        $subject->settings()->save($subjectSettings);
    }


    public function delete($subject){
        $subject->removeAllFiles();
        $subject->delete();
    }

}
