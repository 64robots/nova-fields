<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Field as NovaField;
use Laravel\Nova\Http\Requests\NovaRequest;

class RadioButton extends NovaField
{
    use Configurable;

    /**
     * The base input classes of the field.
     *
     * @var string
     */
    public $inputClasses = 'radio';

    /**
     * The base index classes of the field.
     *
     * @var string
     */
    public $indexClasses = 'text-90';

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-radio';

    /**
     * The text alignment for the field's text in tables.
     *
     * @var string
     */
    public $textAlign = 'center';

    /**
     * Which value should be the default?
     *
     * @param mixed $default
     * @return RadioButton
     */
    public function default($default)
    {
        $this->withMeta(['default' => $default]);

        return $this;
    }

    /**
     * This is a key => value pair of the value => label for the radios.
     *
     * @param mixed $options
     * @return RadioButton
     */
    public function options(array $options)
    {
        $this->withMeta(['options' => $options]);

        return $this;
    }

    /**
     * Should we stack the radios rather than side by side?
     *
     * @return RadioButton
     */
    public function stack()
    {
        $this->withMeta(['stack' => true]);

        return $this;
    }

    /**
     * Sometimes when you have many radios, you need
     * extra margin between them.
     *
     * @return RadioButton
     */
    public function marginBetween()
    {
        $this->withMeta(['marginBetween' => true]);

        return $this;
    }

    /**
     * By default on the detail view, we'll map the value
     * back to the option that was picked, if you do not
     * want that to happen, then skip the transforming here!
     *
     * @return RadioButton
     */
    public function skipTransformation()
    {
        $this->withMeta(['skipTransformation' => true]);

        return $this;
    }

    /**
     * This accepts an array of values, of which the key
     * represents the models value, and the value of the entry
     * is an array of fields which you wish to hide, when the value is of that.
     *
     * [
     *     1 => ['email'] // when the value is 1, it will hide the email field.
     * ]
     *
     * @param array $fields
     * @return RadioField
     */
    public function toggle(array $fields = [])
    {
        $this->withMeta(['toggle' => $fields]);

        return $this;
    }

}
