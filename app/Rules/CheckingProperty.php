<?php

namespace App\Rules;

use App\Models\SubjectProperty;
use Illuminate\Contracts\Validation\Rule;
use Nette\Utils\Json;

class CheckingProperty implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $subjectId;
    public function __construct($subjectId)
    {
        $this->subjectId = $subjectId;
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
        $properties = $value;
        $properties = Json::decode($properties);
        foreach ($properties as $property){
            if(SubjectProperty::where([["id", $property->property_id], ["subject_id", $this->subjectId]])->get()->isEmpty())
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
        return 'The validation error message.';
    }
}
