<?php

namespace App\Http\Resources\User\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderListResource extends JsonResource
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
                "from" => $this->order_time_from,
                "to" => $this->order_time_to
            ],
            "created_at" => date("Y/m/d H:i:s", strtotime($this->created_at)),
//            "day" => [
//                "name" => __(date("l", strtotime($this->order_day))),
//                "date" => $this->order_day
//            ],
//            "MainCategory" => $this->subjects[0]->subCategory->mainCategory->getNameAttribute() ,
//            "ues_online" => $this->subjects[0]->getByOnline()??0,
            "ues_online" => $this->getByOnline()??0,
            "status" => $this->status,
            "pay" => $this->getByPay()??0,
//            "image" => $this->subjects[0]->getFirstMediaFile() ? $this->subjects[0]->getFirstMediaFile()->url : null
            "image" => $this->subjects->first() != null ? $this->subjects->first()->getFirstMediaFile()->url :null

        ];

//        $withTotal = true;
        $total = 0;
//        if($this->subject_guarantee_count)
//            $data["subject_guarantee_count"] = $this->subject_guarantee_count;

        foreach ($this->orderSubjects as $subjectKey => $orderSubject) {
            $priceDetails = $orderSubject->subject->getPriceByQuantity($orderSubject->subject_count);

            switch ($priceDetails["type"]) {
                case "un":
                    $withTotal = false;
                    break;
                case "f":
                    $total += $priceDetails["value"];
                    break;
                case "r":
                    $total += $priceDetails["value"]["range"];
                    break;
            }
            $data["subjects"][$subjectKey] = [
                "name" => $orderSubject->subject->full_name,
                "count" => $orderSubject->subject_count,
            ];

            if($this->pay != 1){
                if($this->finish_at !=null){
                    $data["finish_at"] = $this->finish_at;
                }
            }
        }
//
//            foreach ($orderSubject->properties as $propertyKey => $orderProperty){
//                $total += $orderProperty->value_price;
//            }
//
//        }

//        if($this->status != 3) {
//            $data["total"] = $withTotal ? $total : "unknown";
//        }else{
//            $data["total"] = (int)$this->invoice->total_amount;
//        }

        return $data;
    }
}
