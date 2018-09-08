<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Password as NovaPassword;

class Password extends NovaPassword
{
    use Configurable;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-password';


    /**
     * Sets the mask label
     *
     * @param  string  $placeholder
     * @return $this
     */
    public function maskLabel($label)
    {
        return $this->withMeta(['maskLabel' => $label]);
    }
}
