<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\ID as NovaID;

class ID extends NovaID
{
    use Configurable;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-text';
}
