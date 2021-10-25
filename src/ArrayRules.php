<?php

namespace R64\NovaFields;

use Illuminate\Contracts\Validation\Rule;

class ArrayRules implements Rule
{
    use Configurable;

    /**
     * The base input classes of the field.
     *
     * @var string
     */
    public $inputClasses = 'flex-1 form-control form-input form-input-bordered';

    /**
     * The base index classes of the field.
     *
     * @var string
     */
    public $indexClasses = 'pb-4';


    public $rules = [];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
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
        $input = [$attribute => json_decode($value)];

        $validator = \Validator::make($input, $this->rules, [], [ "$attribute.*" => 'input']);

        $this->message = $validator->errors();

        return $validator->passes();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
