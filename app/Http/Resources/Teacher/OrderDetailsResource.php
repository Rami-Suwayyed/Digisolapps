<?php

namespace App\Http\Resources\Teacher;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use \Illuminate\Support\Facades\Auth;

class OrderDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            "id" => $this->id,
            "time" => [
                "from" => date("h:i A", strtotime($this->order_time_from)),
                "to" => date("h:i A", strtotime($this->order_time_to))
            ],
            "day" => [
                "name" => __(date("l", strtotime($this->order_day))),
                "date" => $this->order_day
            ],
            "location" => [
                "latitude" => $this->order_location_latitude,
                "longitude" => $this->order_location_longitude,
                "name" => $this->order_location_name,
                "apartment_number" => $this->location ? $this->location->apartment_number : Null,
                "building_name" => $this->location ? $this->location->building_name : Null,
                "street" => $this->location ? $this->location->street : Null,
                "additonal" => $this->location ? $this->location->description : Null
            ],
            "status" => $this->status,

        ];


        $data['ues_online'] = $this->ues_online;
        $data['Pay'] = $this->getByPay()??0;
        $data['price'] = $this->price;
//        $data['user_id'] = $this->user_id;
//        $data['user_image'] = $this->user->getFirstMediaFile("profile_photo")->url ?? Null;
//        $data['user_name'] = $this->user->full_name;
//        $data['user_email'] = $this->user->email;
//        $data['user_gender'] = $this->user->getGenderNameAttribute();

//        if($this->status == 2 || $this->status == 3){
            $data['user'] = [
                "user_id" => $this->user_id,
                "full_name" => $this->user->full_name,
                "user_email" => $this->user->email,
                "user_image" => $this->user->getFirstMediaFile("profile_photo")->url ?? Null,
                "phone_number" => $this->user->student->phone_number,
                "user_gender" => $this->user->getGenderNameAttribute(),

            ];
//        }
//        if($this->status != 3){
            foreach ($this->orderSubjects as $subjectKey => $orderSubject){
                $data["subjects"][$subjectKey] = [
                    "order_subject_id" => $orderSubject->id,
                    "name" => $orderSubject->subject->full_name,
                    "name_subject"=>$orderSubject->subject->getNameAttribute(),
                    "name_subCategory"=>$orderSubject->subject->subCategory->getNameAttribute(),
                    "count" => $orderSubject->subject_count,
                    "subject_image" => $orderSubject->subject->getFirstMediaFile() ? $orderSubject->subject->getFirstMediaFile()->url : null
                ];

                if(Auth::user()->type == "t"){
                    $data["subjects"][$subjectKey]["name"] = $orderSubject->subject->teacherGetSubjectNoteAttribute();
                }

                // Properties
                if($orderSubject->properties->isNotEmpty()){
                    foreach ($orderSubject->properties as $propertyKey => $orderProperty){
                        $data["subjects"][$subjectKey]["properties"][] = [
                            "property_name" => $orderProperty->property->name,
                            "value" => $orderProperty->getValue(),
                            "price" => $orderProperty->value_price
                        ];
                    }
                }



                //Notes
                $notes = $orderSubject->notes;
                if($notes){
                    if($notes->text)
                        $data["subjects"][$subjectKey]["notes"]['text'] = $notes->text;
                    if($notes->getFirstMediaFile('voice'))
                        $data["subjects"][$subjectKey]["notes"]['voice'] = $notes->getFirstMediaFile('voice')->url;
                    if($notes->getFirstMediaFile('image'))
                        $data["subjects"][$subjectKey]["notes"]['image'] = $notes->getFirstMediaFile('image')->url;
                }

                //Question
                if($orderSubject->questions->isNotEmpty()){
                    foreach ($orderSubject->questions as $question) {
                        $data['subjects'][$subjectKey]['questions'][] = [
                            "questions_name" => $question->question->text,
                            "value" => $question->answer ? __("Yes") : __("No"),
                            "note" => $question->note ?? Null
                        ];

                    }
                }
            }
//        }else{
//            foreach ($this->invoice->subjects as $subjectKey => $orderSubject) {
//
//                $data["subjects"][$subjectKey] = [
//                    "name" => $orderSubject->subject->full_name,
//                    "count" => $orderSubject->count,
//                    "price" => ["type" => "f","value" => (double)$orderSubject->price],
//                    "image" => $orderSubject->subject->getFirstMediaFile() ? $orderSubject->subject->getFirstMediaFile()->url : null
//                ];
//                $guaranteeTime = strtotime($this->finish_at) + $orderSubject->subject->guarantee_days * 24 * 60 * 60;
//                if($guaranteeTime > time()) {
//                    if (Carbon::createFromTimestamp($guaranteeTime)->diffInDays() != 0) {
//                        $data["subjects"][$subjectKey]["guarantee_time"] = Carbon::createFromTimestamp($guaranteeTime)->diffInDays();
//                    } else
//                        $data["subjects"][$subjectKey]["guarantee_time"] = Carbon::createFromTimestamp($guaranteeTime)->diffForHumans(['parts' => 2]);
//                }
//            }
//        }


        if($this->canceled && $this->canceled->teacher_id == Auth::user()->id){
            switch ($this->canceled->type){
                case 1:
//                         $this->canceled->afterReview->review_price;
                    $data['review'] ="one";
                    break;
                case 2:
//                    $data['review'] = $this->canceled->beforeReview->reason_letter;
                    $data['review'] ="Two";
                    break;
            }
        }
        return $data;
    }
}
