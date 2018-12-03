<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Boolean as NovaBoolean;

class Boolean extends NovaBoolean
{
    use Configurable;

    /**
     * The base input classes of the field.
     *
     * @var string
     */
    public $inputClasses = 'py-2';

    /**
     * The base index classes of the field.
     *
     * @var string
     */
    public $indexClasses = 'text-center';

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-boolean';

    /**
     * Set the classes that should be applied to the status dot instead of default ones.
     *
     * @param  string  $classes
     * @return $this
     */
    public function dotClasses($classes)
    {
        return $this->withMeta(['dotClasses' => $classes]);
    }

    /**
     * Set the class that should be applied to the dot when boolean is true.
     *
     * @param  string  $class
     * @return $this
     */
    public function successClass($class)
    {
        return $this->withMeta(['successClass' => $class]);
    }

    /**
     * Set the class that should be applied to the dot when boolean is false.
     *
     * @param  string  $class
     * @return $this
     */
    public function dangerClass($class)
    {
        return $this->withMeta(['dangerClass' => $class]);
    }

    /**
     * Set the label near to the dot when boolean is true.
     *
     * @param  string  $label
     * @return $this
     */
    public function yesLabel($label)
    {
        return $this->withMeta(['yesLabel' => $label]);
    }

    /**
     * Set the label near to the dot when boolean is false.
     *
     * @param  string  $label
     * @return $this
     */
    public function noLabel($label)
    {
        return $this->withMeta(['noLabel' => $label]);
    }

    /**
     * Indicate that the label near to the dot should be hidden.
     *
     * @return $this
     */
    public function hideBooleanLabel()
    {
        return $this->withMeta(['hideBooleanLabel' => true]);
    }
}
