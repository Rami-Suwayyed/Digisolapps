<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExpierTime implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $error_message;
    public function __construct()
    {
        //
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
        $this->error_message = "The {$attribute} is not valid";
        if(preg_match("/^([0-1][0-9])\/[0-9][0-9]$/", $value, $matchs)){
            if($matchs[1] > 12)
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
    public function message()
    {
        return $this->error_message;
    }
}
