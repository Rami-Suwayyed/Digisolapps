<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Http\Controllers\Controller;
use App\Models\GeneralSubjectQuestion;
use App\Repositories\GeneralSubjectQuestionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GeneralSubjectQuestionController extends Controller
{
    protected $repository;
    public function __construct(GeneralSubjectQuestionRepository $repository)
    {
        $this->repository = $repository;
    }


    public function index(){
        $this->permissionsAllowed("create-general-subjects-questions", "update-general-subjects-questions");

        $data["questions"] = GeneralSubjectQuestion::all();
        return view("admin.general_question_subject.index", $data);
    }

    public function create(){
        return view("admin.general_question_subject.create");
    }

    public function store(Request $request) {
        $this->permissionsAllowed("create-general-subjects-questions");

        $valid = Validator::make($request->all(), $this->repository->rules(), [], $this->repository->columns());
        if($valid->fails()){
            return redirect()->route("admin.subject_general_question.create")->withInput($request->all())->withErrors($valid->errors()->messages());
        }

        $question = $this->repository->createQuestion($request);

        $message = (new SuccessMessage())
                    ->title("Created Successfully")
                    ->body("The Question Has Been Created Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.subject_general_question.index");
    }

    public function edit(Request $request){
        $this->permissionsAllowed("update-general-subjects-questions");

        $data["question"] = GeneralSubjectQuestion::findOrFail($request->id);
        return view("admin.general_question_subject.edit", $data);
    }

    public function update(Request $request){
        $this->permissionsAllowed("update-general-subjects-questions");

        $question = GeneralSubjectQuestion::findOrFail($request->id);
        $valid = Validator::make($request->all(), $this->repository->rules(), [], $this->repository->columns());
        if($valid->fails()){
            return redirect()->route("admin.subject_general_question.edit", ["id" => $question->id])->withInput($request->all())->withErrors($valid->errors()->messages());
        }

        $this->repository->saveQuestion($request, $question);

        $message = (new SuccessMessage())
            ->title("Updated Successfully")
            ->body("The Question Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.subject_general_question.index");
    }

    public function destroy(Request $request){
        $this->permissionsAllowed("admin-control");

        GeneralSubjectQuestion::findOrFail($request->id)->delete();
        $message = (new DangerMessage())
            ->title("Removed Successfully")
            ->body("The Question Has Been Removed Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.subject_general_question.index");
    }
}
