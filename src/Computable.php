<?php

namespace R64\NovaFields;

trait Computable
{
    /**
     * The callback that computes the value.
     *
     * @var string
     */
    public $computeCallback;

    /**
     * Determines if return a collection of options for a select field.
     *
     * @var boolean
     */
    public $computeOptions = false;

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

    /**
     * Set the compute callback for select options.
     *
     * @var $this
     */
    public function computeOptionsUsing($callback)
    {
        $this->computeOptions = true;
        $this->computeCallback = $callback;

        return $this;
    }
}
