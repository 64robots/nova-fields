<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\ID as NovaID;

class ID extends NovaID
{
    use Configurable;

    /**
     * The base input classes of the field.
     *
     * @var string
     */
    public $inputClasses = '';

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
    public $component = 'nova-fields-text';
}
