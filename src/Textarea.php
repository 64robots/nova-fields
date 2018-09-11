<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Textarea as NovaTextarea;

class Textarea extends NovaTextarea
{
    use Configurable;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-textarea';

    /**
     * Set the Show Content label
     *
     * @param  string  $label
     * @return $this
     */
    public function showContentLabel($label)
    {
        return $this->withMeta(['showContentLabel' => $label]);
    }

    /**
     * Set the Hide Content label
     *
     * @param  string  $label
     * @return $this
     */
    public function hideContentLabel($label)
    {
        return $this->withMeta(['hideContentLabel' => $label]);
    }
}
