<?php

namespace R64\NovaFields;

class Autocomplete extends Select
{
    use Computable;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-autocomplete';
}
