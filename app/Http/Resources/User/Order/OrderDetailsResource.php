<?php

namespace App\Http\Resources\User\Order;

use App\Http\Resources\User\Order\DetailsParts\TeacherAccepted;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

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
                "from" => $this->order_time_from,
                "to" => $this->order_time_to
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
            "has_invoice" => (bool)$this->invoice,
            "status" => $this->status,
            "time_to_start" => Carbon::createFromTimestamp(strtotime($this->order_day . " " . $this->order_time_from))->diffForHumans(['parts' => 2]),
            "time_start" => Carbon::createFromTimestamp(strtotime($this->order_day . " " . $this->order_time_from))
        ];

//        if($this->status != 3){
        $data["created_at"] = date("Y/m/d H:i:s", strtotime($this->created_at));
        $data['activate'] = $this->activation;
        $data['user_id'] = $this->user_id;
        $data['ues_online'] = $this->ues_online;
        if($this->ues_online == 1) {
            $data["online-zoom"] = $this->linkClass->link ?? "https://zoom.us/";
            $data['linkClass'] = $this->linkClass->link ?? null;
        }
        $data['Pay'] = $this->getByPay()??0;
        $data['teacher_name'] = $this->userT->full_name;
        $data['teacher_id'] = $this->teacher_id;
        $data['teacher_price'] = 0.2;
        $data['teacher_ues_price'] = $this->userT->teacher->ues_price;
        $data['teacher_rating'] = $this->userT->rating->avg("number_rating")??0.0;
        $data['image_teacher'] = $this->userT->getFirstMediaFile("profile_photo")->url;
//        }
        $withTotal = true;
        $total = 0;

        if($this->promocode){
            if($this->promocode->type == "percentage")
                $data['discount'] = $this->promocode->value . "%";
            else
                $data['discount'] = $this->promocode->value . "IQ";
        }


//        if(in_array(((int)$this->status), [ 1, 2, 3]))
//            $data["teacher"] = TeacherAccepted::make($this);


        foreach ($this->orderSubjects as $subjectKey => $orderSubject){
            $priceDetails = $orderSubject->subject->getPriceByQuantity($orderSubject->subject_count);

            switch ($priceDetails["type"]){
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
                "order_subject_id" => $orderSubject->id,
                "name" => $orderSubject->subject->full_name,
                "name_subject"=>$orderSubject->subject->getNameAttribute(),
                "name_subCategory"=>$orderSubject->subject->subCategory->getNameAttribute(),
                "count" => $orderSubject->subject_count,
                "price" => $priceDetails,
                "image" => $orderSubject->subject->getFirstMediaFile() ? $orderSubject->subject->getFirstMediaFile()->url : null
            ];

            if($this->status == 3){
                $guaranteeTime = strtotime($this->finish_at) + $orderSubject->guarantee_days * 24 * 60 * 60;
                if($guaranteeTime > time()) {
                    if (Carbon::createFromTimestamp($guaranteeTime)->diffInDays() != 0)
                        $data["subjects"][$subjectKey]["guarantee_time"] = Carbon::createFromTimestamp($guaranteeTime)->diffInDays();
                    else
                        $data["subjects"][$subjectKey]["guarantee_time"] = Carbon::createFromTimestamp($guaranteeTime)->diffForHumans(['parts' => 2]);
                }
            }

            // Properties
            foreach ($orderSubject->properties as $propertyKey => $orderProperty){
                $total += $orderProperty->value_price;
                $data["subjects"][$subjectKey]["properties"][] = [
                    "property_name" => $orderProperty->property->name,
                    "value" => $orderProperty->getValue(),
                    "price" => $orderProperty->value_price
                ];
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

        }
        $data["pay"] = $this->getByPay()??0;
        if($this->pay != 1){
            if($this->finish_at !=null){
                $data["finish_at"] = $this->finish_at;
            }
        }

        if($this->status != 3) {
//            $data["total"] = $withTotal ? $total : "unknown";
            $data["total"] =0;
        }else{
//            $data["total"] = (int)$this->invoice->total_amount;\
            $data["total"]=0;
        }
        return $data;
    }
}
