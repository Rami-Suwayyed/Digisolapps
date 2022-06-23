<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\GeneralSubjectQuestion;
use App\Models\GeneralSubjectQuestionForm;
use App\Repositories\GeneralSubjectQuestionFormRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GeneralSubjectQuestionFormController extends Controller
{
    protected GeneralSubjectQuestionFormRepository $repository;
    public function __construct(GeneralSubjectQuestionFormRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request) {
        $data = $this->repository->adminIndex($request);
        return view("admin.general_question_subject_form.index", $data);
    }

    public function edit(Request $request){
        $data["property"] = GeneralSubjectQuestionForm::getByIdAndQuestionId($request->question_id,  $request->property_id)->firstOrFail();
        return view("admin.general_question_subject_form.edit", $data);
    }

    public function update(Request $request){
        $rules = $this->repository->rules($request);
        unset($rules["type"]);
        $property = GeneralSubjectQuestionForm::getByIdAndQuestionId($request->question_id,  $request->property_id)->firstOrFail();
        $valid = Validator::make($request->all(), $rules, [], $this->repository->columns());
        if($valid->fails())
            return redirect()->route("admin.subject_general_question.form.edit", ["question_id" => $request->question_id, "property_id" => $request->property_id])
                ->withInput($request->all())->withErrors($valid->errors()->messages());

        $this->repository->saveProperty($request, $property);

        $message = (new SuccessMessage())
            ->title("Updated Successfully")
            ->body("The Property Form Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.subject_general_question.form.index", ["question_id" => $request->question_id]);
    }

    public function destroy(Request $request){
        GeneralSubjectQuestionForm::findOrFail($request->property_id)->delete();
        $message = (new DangerMessage())
            ->title("Removed Successfully")
            ->body("The Property Form Has Been Removed Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.subject_general_question.form.index", ["question_id" => $request->question_id]);
    }
}
