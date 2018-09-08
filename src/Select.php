<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Select as NovaSelect;

class Select extends NovaSelect
{
    use Configurable;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-select';
}
