<?php

namespace App\Repositories;

use App\Helpers\Notification\Types\AdminNotification;
use App\Models\OrderSubject;
use App\Models\OrderUnderGuaranteeSubjectProblem;
use App\Rules\Custom\CheckOrderSubjectGuaranteeIsValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UnderGuaranteeProblemRepository
{

    protected function rules(): array
    {
        return [
            "order_subject_id" => ["required", "exists:order_subjects,id", new CheckOrderSubjectGuaranteeIsValid(Auth::guard("api")->user()->id)],
            "letter" => ["required", "max:500"],
            "image" => ["nullable", "image"],
            "voice" => ["nullable", "file"]
        ];
    }

    public function validation(Request $request): array
    {
        $result["fails"] = false;
        $valid = Validator::make($request->all(), $this->rules());
        if($valid->fails()){
            $result["fails"] = true;
            $result["errors"] = $valid->errors()->messages();
        }
        return $result;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store(Request $request){
        $orderSubject = OrderSubject::find($request->order_subject_id);
        $subjectUnderGuaranteeProblem = new OrderUnderGuaranteeSubjectProblem();
        $subjectUnderGuaranteeProblem->letter = $request->letter;
        $subjectUnderGuaranteeProblem->order_subject_id = $request->order_subject_id;
        $subjectUnderGuaranteeProblem->save();
        if($request->file("image"))
            $subjectUnderGuaranteeProblem->saveMedia($request->file("image"), "image");
        if($request->file("voice"))
            $subjectUnderGuaranteeProblem->saveMedia($request->file("voice"), "voice");

//        $adminNotification = new AdminNotification("admin.under_guarantee_problem");
//        $adminNotification->setBodyArgs(["orderId" => $orderSubject->order->id, "subjectId" => $orderSubject->subject->id, "fullName" => Auth::guard("api")->user()->full_name]);
//        $adminNotification->setTitleArgs(["orderId" => $orderSubject->order->id]);
//        $adminNotification->send();
    }

}
