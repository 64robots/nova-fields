<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Trix as NovaTrix;

class Trix extends NovaTrix
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
    public $indexClasses = '';

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-trix';
}
