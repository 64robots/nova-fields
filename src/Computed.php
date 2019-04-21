<?php

namespace R64\NovaFields;

class Computed extends Text
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-computed';

    /**
     * The callback that computes the value.
     *
     * @var string
     */
    public $computeCallback;

    /**
     * Set the compute callback.
     *
     * @var $this
     */
    public function computeUsing($callback)
    {
        $this->computeCallback = $callback;

        return $this;
    }
}
