<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Text as NovaText;

class BlankDiv extends NovaText
{
    use Configurable;

    /**
     * The base input classes of the field.
     *
     * @var string
     */
    public $inputClasses = 'w-full';

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
    public $component = 'nova-fields-blank-div';
}
