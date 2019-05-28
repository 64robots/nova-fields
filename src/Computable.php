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
     * The callback that computes the select options.
     *
     * @var string
     */
    public $computeOptionsCallback;

    /**
     * Set the compute callback.
     *
     * @var $this
     */
    public function computeUsing($callback)
    {
        $this->computeCallback = $callback;

        return $this->withMeta(['compute' => true]);
    }

    /**
     * Set the compute callback for select options.
     *
     * @var $this
     */
    public function computeOptionsUsing($callback)
    {
        $this->computeOptionsCallback = $callback;

        return $this->withMeta(['computeOptions' => true]);
    }

    /**
     * Whether disable computation on created hook.
     *
     * @var $this
     */
    public function disableComputeOnCreated()
    {
        return $this->withMeta(['disableComputeOnCreated' => true]);
    }
}
