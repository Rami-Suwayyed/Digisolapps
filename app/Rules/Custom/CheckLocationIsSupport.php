<?php

namespace App\Rules\Custom;

use App\Services\SupportLocationService;
use Illuminate\Contracts\Validation\Rule;

class CheckLocationIsSupport implements Rule
{
    protected $latitude, $longitude;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
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
        return (new SupportLocationService())->checkIsSupport($this->latitude, $this->longitude);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('the location is not support.');
    }
}
