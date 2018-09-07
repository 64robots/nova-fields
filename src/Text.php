<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Text as NovaText;

class Text extends NovaText
{
    use Configurable;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-text';
}
