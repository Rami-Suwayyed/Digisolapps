<?php

namespace App\Rules\Custom;

use App\Models\PropertySelection;
use App\Models\Subject;
use App\Models\SubjectProperty;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class OrderSubjects implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected Request $request;
    protected  $messages;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $subjectInfo = $value;
        $subject =
        $subject = Subject::find((int)$subjectInfo["subject_id"] ?? null);
        $rules = [
            "subject_id"    => ["required", "numeric"],
            "counter"       => ["required", "numeric"],
            "properties"    => ["array"],
            "questions"     => ["array"]
        ];
        if($subject && $subject->properties->isNotEmpty())
            array_unshift($rules["properties"], "required");
        if($subject && $subject->questions->isNotEmpty())
            array_unshift($rules["questions"], "required");

        $valid = Validator::make($subjectInfo, $rules);
        if($valid->fails()){
            $this->messages = $valid->errors()->messages();
            return false;
        }

        if(!$subject){
            $this->messages = __("the subject is not valid.");
            return false;
        }



        if($subject->settings->is_order_notes_required){
            if(!isset($subjectInfo["notes"]) || (!isset($subjectInfo["notes"]["image"]) && !isset($subjectInfo["notes"]["voice"]) && !isset($subjectInfo["notes"]["text"]))){
                $this->messages = __( "The notes is required.");
                return false;
            }
        }
        if(isset($subjectInfo["notes"])){
            $rules = [
                "image" => isset($subjectInfo["notes"]["image"])  ? ["image"] : [],
                "voice" => isset($subjectInfo["notes"]["voice"])  ? ["file"] : [],
                "text" => isset($subjectInfo["notes"]["text"])  ? ["max:500"] : []
            ];
            $valid = Validator::make($subjectInfo["notes"], $rules);
            if($valid->fails()){
                $this->messages = $valid->errors()->messages();
                return false;
            }
        }


        if(isset($subjectInfo["properties"])){
            foreach ($subjectInfo["properties"] as $index => $property){
                $rules = [
                    "id"    => ["required", "numeric"],
                    "value"    => ["required"],
                ];

                $valid = Validator::make($property, $rules, [], ["value" => "property_value." . $index]);
                if($valid->fails()){
                    $this->messages = $valid->errors()->messages();
                    return false;
                }
                $subjectProperty = SubjectProperty::selectBuilder()->bySubject($subjectInfo["subject_id"])->byId($property["id"])->first();
                if(!$subjectProperty){
                    $this->messages = __("the property is invalid.");
                    return false;
                }
                $rules = [];
                if($subjectProperty->type === "SW"){
                    if(!in_array($property["value"], ["0","1"])){
                        $this->messages = __("the property value is invalid.");
                        return false;
                    }
                }else{
                    if(!PropertySelection::where(["id" => (int)$property["value"], "property_id" => $property["id"]])->first()){
                        $this->messages = __("the property value is invalid.");
                        return false;
                    }
                }
            }
        }

        if(isset($subjectInfo["questions"])){
            foreach ($subjectInfo["questions"] as $question){
                $rules = [
                    "id"        => ["required", "numeric", "exists:general_subjects_questions,id"],
                    "answer"    => ["required", "in:0,1"],
                    "text"      => ["max:255"]
                ];
                $valid = Validator::make($question, $rules);
                if($valid->fails()){
                    $this->messages["questions"] = $valid->errors()->messages();
                    return false;
                }
            }
        }

    return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->messages;
    }
}
