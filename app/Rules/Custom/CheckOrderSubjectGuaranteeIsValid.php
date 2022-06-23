<?php

namespace App\Rules\Custom;

use App\Models\OrderSubject;
use Illuminate\Contracts\Validation\Rule;

class CheckOrderSubjectGuaranteeIsValid implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $userId;
    public function __construct($userId)
    {
        $this->userId = $userId;
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
        $orderSubject = OrderSubject::find($value);
        if($orderSubject){
            $order = $orderSubject->order;
            if($order->user_id != $this->userId)
                return false;
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('The subject is invalid.');
    }
}
