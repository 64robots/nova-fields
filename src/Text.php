<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Text as NovaText;

class Text extends NovaText
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
    public $indexClasses = 'whitespace-no-wrap';

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-text';

    /**
     * Set input mask
     *
     * @param  String  $mask
     * @return $this
     */
    public function mask(String $mask = '')
    {
        return $this->withMeta([__FUNCTION__ => $mask]);
    }

    /**
     * Set raw mode when save
     *  TRUE    Send value without mask ( RAW )
     *  FALSE   Send value with mask
     *
     * @param  bool  $raw
     * @return $this
     */
    public function raw(bool $raw = true)
    {
        return $this->withMeta([__FUNCTION__ => $raw]);
    }
}
