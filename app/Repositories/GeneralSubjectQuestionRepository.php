<?php

namespace App\Repositories;



use App\Models\GeneralSubjectQuestion;
use Illuminate\Http\Request;

class GeneralSubjectQuestionRepository
{

    public function rules(): array
    {
        return [
            "text_en" => ["required","max:255"],
            "text_ar" => ["required","max:255"],
        ];
    }
    public function columns(): array
    {
        return [
            "text_en" => "english question text",
            "text_ar" => "english question text",
        ];
    }

    public function createQuestion(Request $request){
        $question = new GeneralSubjectQuestion();
        $question->has_form = isset($request->has_form);
        $this->saveQuestion($request, $question);
        return $question;
    }

    public function saveQuestion(Request $request, GeneralSubjectQuestion & $question){
        $question->text_en = $request->text_en;
        $question->text_ar = $request->text_ar;
        $question->save();
    }
}
