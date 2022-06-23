<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            "created_at" => $this->created_at,
            "time" => [
                "from" => date("h:i A", strtotime($this->order_time_from)),
                "to" => date("h:i A", strtotime($this->order_time_to))
            ],
            "day" => [
                "name" => __(date("l", strtotime($this->order_day))),
                "date" => $this->order_day
            ],
//            "image" => $this->orderSubjects[0]->subject->subCategory->mainCategory->getFirstMediaFile()->url,
//            "location" => [
//                "latitude" => $this->order_location_latitude,
//                "longitude" => $this->order_location_longitude,
//                "name" => $this->order_location_name,
//                "street" => $this->location ? $this->location->street : Null
//            ],
            "status" => $this->status,
            "customer" => [
                "id" => $this->user->id,
                "full_name" => $this->user->full_name
            ],
            "ues_online" => $this->getByOnline()??0,
            "pay" => $this->getByPay()??0,
            "image" => $this->subjects->first() != null ? $this->subjects->first()->getFirstMediaFile()->url :null
        ];

        foreach ($this->orderSubjects as $subjectKey => $orderSubject){
//            $data["subjects"][$subjectKey] = [
//                "name" => $orderSubject->subject->teacherGetSubjectNoteAttribute(),
//                "count" => $orderSubject->subject_count,
//            ];
            $data["subjects"][$subjectKey] = [
                "name" => $orderSubject->subject->full_name,
//                "subject_image" => $orderSubject->subject->getFirstMediaFile() ? $orderSubject->subject->getFirstMediaFile()->url : null,
                "count" => $orderSubject->subject_count,
            ];

        }
        if($this->pay != 1){
            if($this->finish_at !=null){
                $data["finish_at"] = $this->finish_at;
            }
        }

        if($this->subject_guarantee_count)
            $data["subject_guarantee_count"] = $this->subject_guarantee_count;

        if($this->canceled){
            $orderCansel = $this->canceled;
            if($orderCansel->type == 1)
                $data['reason_letter'] = $orderCansel->beforeReview->reason_letter;
            else
                $data['review_price'] = $orderCansel->afterReview->review_price;
        }


        if($this->status == -1)
            $data['created_at'] = date("Y-m-d H:i A", strtotime($this->created_at));

        return $data;
    }
}
