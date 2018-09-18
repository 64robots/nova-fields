<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Trix as NovaTrix;

class Trix extends NovaTrix
{
    use Configurable;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-trix';
}
