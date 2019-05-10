<?php

namespace R64\NovaFields\Http\Controllers;

class ComputedValues
{
    protected $values;

    public function __construct($values)
    {
        return $this->values = (object) $values;
    }

    public function __get($key)
    {
        return property_exists($this->values, $key) ? $this->values->$key : null;
    }
}
