<?php

namespace R64\NovaFields;

use R64\NovaFields\Configurable;
use Laravel\Nova\Fields\Heading as NovaHeading;

class Heading extends NovaHeading
{
    use Configurable;
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'heading';

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  mixed|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct(null, $attribute, $resolveCallback);

        $this->withMeta(['value' => $name]);
        $this->hideFromIndex();
        $this->withMeta(['asHtml' => false]);
    }

    /**
     * Display the field as raw HTML using Vue.
     *
     * @return $this
     */
    public function asHtml()
    {
        return $this->withMeta(['asHtml' => true]);
    }

    /**
     * The base input classes of the field.
     *
     * @var string
     */
    public $inputClasses = '';

    /**
     * The base index classes of the field.
     *
     * @var string
     */
    public $indexClasses = '';
}
