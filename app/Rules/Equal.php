<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Equal implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected float $match;
    public function __construct($value)
    {
        $this->match = (float)$value;
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
        $value = (float) $value;

        if($value !== $this->match)
            return false;
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('The :attribute must be equal :match', ["match" => $this->match]);
    }
}
