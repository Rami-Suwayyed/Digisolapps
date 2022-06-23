<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            "subjects_total_price" => $this->total_price_subjects,
            "total_price_extra_subjects" => $this->total_price_extra_subjects,
            "total_price_parts" => $this->total_price_parts,
            "sub_totals" => $this->getSubTotals(),
            "tax" => $this->tax,
            "total_amount" => $this->total_amount,
            "discount" => $this->discount,
            "activate" => $this->accepting,
            "use_wallet" => $this->wallets,
        ];

        foreach ($this->subjects as $index => $invoiceSubject){
            $data["subjects"][$index]["name"] = $invoiceSubject->subject->name;
            $data["subjects"][$index]["count"] = $invoiceSubject->count;
            $data["subjects"][$index]["price"] = $invoiceSubject->price;
        }
        foreach ($this->extraSubjects as $index => $extraSubject){
            if($extraSubject->name == Null)
                break;
            $data["extra_subjects"][$index]["name"] = $extraSubject->name;
            $data["extra_subjects"][$index]["count"] = $extraSubject->count;
            $data["extra_subjects"][$index]["price"] = $extraSubject->price;
        }
        foreach ($this->parts as $index => $part){
            if($part->name == Null)
                break;
            $data["parts"][$index]["name"] = $part->name;
            $data["parts"][$index]["count"] = $part->count;
            $data["parts"][$index]["price"] = $part->price;
        }
        return $data;
    }
}
