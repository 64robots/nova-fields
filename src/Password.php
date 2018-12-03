<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Password as NovaPassword;

class Password extends NovaPassword
{
    use Configurable;

    /**
     * The base input classes of the field.
     *
     * @var string
     */
    public $inputClasses = 'w-full form-control form-input form-input-bordered';

    /**
     * The base index classes of the field.
     *
     * @var string
     */
    public $indexClasses = 'font-bold';

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
