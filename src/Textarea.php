<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Textarea as NovaTextarea;

class Textarea extends NovaTextarea
{
    use Configurable;

    /**
     * The base input classes of the field.
     *
     * @var string
     */
    public $inputClasses = 'w-full form-control form-input form-input-bordered py-3 min-h-textarea';

    /**
     * The base index classes of the field.
     *
     * @var string
     */
    public $indexClasses = 'whitespace-no-wrap';

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
