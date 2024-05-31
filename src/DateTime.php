<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\DateTime as NovaDateTime;

class DateTime extends NovaDateTime
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
    public $component = 'nova-fields-date-time';

    /**
     * Indicate that the timezone should be hidden in form view.
     *
     * @return $this
     */
    public function hideTimezone(): DateTime
    {
        return $this->withMeta(['hideTimezone' => true]);
    }
}
