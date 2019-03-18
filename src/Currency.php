<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Currency as NovaCurrency;

class Currency extends NovaCurrency
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
    public $component = 'nova-fields-text';


    /**
     * Indicate that value should be displayed as danger when < 0 .
     *
     * @return $this
     */
    public function colors()
    {
        return $this->withMeta(['colors' => true]);
    }
}
