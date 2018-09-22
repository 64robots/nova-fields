<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Number as NovaNumber;

class Number extends NovaNumber
{
    use Configurable;

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
