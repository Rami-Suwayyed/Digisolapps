<?php

namespace App\Rules\Custom\Invoice;

use App\Models\Invoice\Invoice;
use App\Models\Order;
use Illuminate\Contracts\Validation\Rule;

class InvoiceOrder implements Rule
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
    public function passes($attribute, $value)
    {
        $order = Order::find($value);
        if(!$order){
            $this->errorMessage = __("The order doesn't exists.");
            return false;
        }
        if($order->status != 2){
            $this->errorMessage = __("The order is invalid.");
            return false;
        }
        if(Invoice::where("order_id",$order->id)->first()){
            $this->errorMessage = __("The invoice already registered.");
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
