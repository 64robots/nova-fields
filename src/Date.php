<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Date as NovaDate;

class Date extends NovaDate
{
    use Configurable;

    /**
     * The base input classes of the field.
     *
     * @var string
     */
    public $inputClasses = 'w-full form-control form-input form-input-bordered';

    /**
     * The base index classes of the field.
     *
     * @var string
     */
    public $indexClasses = 'whitespace-no-wrap';

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-date';

    public function format($value){
        return $this->withMeta([__FUNCTION__ => $value]);
    }

    public function pickerDisplayFormat($value){
        return $this->withMeta([__FUNCTION__ => $value]);
    }
}
