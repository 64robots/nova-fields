<?php

namespace R64\NovaFields\Traits;

trait CoverHelpers
{
    /**
     * Indicates whether the image should be fully rounded or not.
     *
     * @var bool
     */
    public $rounded = false;

    /**
     * Display the image thumbnail with full-rounded edges.
     *
     * @return void
     */
    public function rounded()
    {
        $this->rounded = true;

        return $this;
    }

    /**
     * Display the image thumbnail with square edges.
     *
     * @return $this
     */
    public function squared()
    {
        $this->rounded = false;

        return $this;
    }

    /**
     * Determine whether the field should have rounded corners.
     *
     * @return bool
     */
    public function isRounded()
    {
        return $this->rounded == true;
    }

    /**
     * Determine whether the field should have squared corners.
     *
     * @return bool
     */
    public function isSquared()
    {
        return $this->rounded == false;
    }
}
