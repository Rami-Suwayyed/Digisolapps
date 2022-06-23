<?php

namespace App\Repositories;



use App\Models\GeneralSubjectQuestion;
use App\Models\GeneralSubjectQuestionForm;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GeneralSubjectQuestionFormRepository
{

    public function rules(Request $request): array
    {
        return [
            "name_en"   => ["required","max:255"],
            "name_ar"   => ["required","max:255"],
            "type"      => ["required", "in:ta,f,v", Rule::unique("general_subjects_questions_forms", 'type')
                                                        ->where("question_id", $request->question_id)],
        ];
    }
    public function columns(): array
    {
        return [
            "name_en" => "english property name",
            "name_ar" => "arabic property name",
        ];
    }

    public function adminIndex(Request $request){
        $data = [];
        $this->getAllProperties($request->question_id, $data);
        $hasProperties = [];
        foreach ($data["question"]->formProperties as $property)
            $hasProperties[$property->type] = true;
        $data["hasProperties"] = $hasProperties;
        unset($hasProperties);
        return $data;
    }

    public function getAllProperties($questionId, &$data){
        $data["question"] = GeneralSubjectQuestion::getQuestionIfHasForm($questionId)->firstOrFail();
        $data["properties"] = $data["question"]->formProperties;
    }

    public function createProperty(Request $request, $question){
        $property = new GeneralSubjectQuestionForm();
        $property->question_id = $question->id;
        $property->type     = $request->type;
        $this->saveProperty($request, $property);
    }


    public function saveProperty(Request $request, GeneralSubjectQuestionForm $property){
        $property->name_en  = $request->name_en;
        $property->name_ar  = $request->name_ar;
        $property->is_required = isset($request->is_required);
        $property->save();
    }

}
