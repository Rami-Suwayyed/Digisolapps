<?php

namespace App\Rules\Custom\Invoice;

use App\Models\Subject;
use App\Rules\Equal;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class InvoiceSubjects implements Rule
{
    public $errorMessage;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->errorMessage = __("data invalid.");

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
            $subject = Subject::find($value["id"] ?? null);
            if(!$subject){
                $this->errorMessage = __("The subject doesn't exists.");
                return false;
            }
            $rules = [
                    "price" => ["required", "numeric"],
                    "count" => ["required", "numeric"]
            ];
            $messages = [];
            $priceData = $subject->getPriceByQuantity($value["count"] ?? 0);
            if($priceData["type"] == "r"){
                $rules["price"][] = "between:" . $priceData["value"]["from"] . "," . $priceData["value"]["to"];
            }
            else if($priceData["type"] == "f")
                $rules["price"][] = new Equal((double)$priceData["value"]);

            $valid = Validator::make($value, $rules, ["price.in" => "The :attribute must equal :"]);
            if($valid->fails()){
                $this->errorMessage = $valid->errors()->first();
                return false;
            }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage;
    }
}
