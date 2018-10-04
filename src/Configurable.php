<?php

namespace R64\NovaFields;

trait Configurable
{
    /**
     * The base container classes of the field.
     *
     * @var string
     */
    public $wrapperClasses = 'flex border-b border-40';

    /**
     * The base field classes of the field.
     *
     * @var string
     */
    public $fieldClasses = 'w-1/2 px-8 py-6';

    /**
     * The base field classes for the detail view.
     *
     * @var string
     */
    public $panelFieldClasses = 'w-3/4 py-4';

    /**
     * The base wrapper classes for label.
     *
     * @var string
     */
    public $labelClasses = 'w-1/5 px-8 py-6';

    /**
     * The base label classes for the detail view.
     *
     * @var string
     */
    public $panelLabelClasses = 'w-1/4 py-4';

    /**
     * The base excerpt classes.
     *
     * @var string
     */
    public $excerptClasses = 'cursor-pointer dim inline-block text-primary font-bold';

    /**
     * Set the container classes that should be applied instead of default ones.
     *
     * @param  string  $classes
     * @return $this
     */
    public function wrapperClasses($classes)
    {
        $this->wrapperClasses = $classes;

        return $this;
    }

    /**
     * Add classes to the base container classes.
     *
     * @param  string  $classes
     * @return $this
     */
    public function addWrapperClasses($classes)
    {
        $this->wrapperClasses = "{$this->wrapperClasses} {$classes}";

        return $this;
    }

    /**
     * Set the input classes that should be applied instead of default ones.
     *
     * @param  string  $classes
     * @return $this
     */
    public function inputClasses($classes)
    {
        $this->inputClasses = $classes;

        return $this;
    }

    /**
     * Add classes to the base input classes.
     *
     * @param  string  $classes
     * @return $this
     */
    public function addInputClasses($classes)
    {
        $this->inputClasses = "{$this->inputClasses} {$classes}";

        return $this;
    }

    /**
     * Set the field wrapper classes that should be applied instead of default ones.
     *
     * @param  string  $classes
     * @return $this
     */
    public function fieldClasses($classes)
    {
        $this->fieldClasses = $classes;

        return $this;
    }

    /**
     * Add classes to the base field classes.
     *
     * @param  string  $classes
     * @return $this
     */
    public function addFieldClasses($classes)
    {
        $this->fieldClasses = "{$this->fieldClasses} {$classes}";

        return $this;
    }

    /**
     * Set the field wrapper classes in detail panel that should be applied instead of default ones.
     *
     * @param  string  $classes
     * @return $this
     */
    public function panelFieldClasses($classes)
    {
        $this->panelFieldClasses = $classes;

        return $this;
    }

    /**
     * Add classes to the base field classes for detail view.
     *
     * @param  string  $classes
     * @return $this
     */
    public function addPanelFieldClasses($classes)
    {
        $this->panelFieldClasses = "{$this->panelFieldClasses} {$classes}";

        return $this;
    }

    /**
     * Set the label wrapper classes that should be applied instead of default ones.
     *
     * @param  string  $classes
     * @return $this
     */
    public function labelClasses($classes)
    {
        $this->labelClasses = $classes;

        return $this;
    }

    /**
     * Add classes to the base label wrapper classes.
     *
     * @param  string  $classes
     * @return $this
     */
    public function addLabelClasses($classes)
    {
        $this->labelClasses = "{$this->labelClasses} {$classes}";

        return $this;
    }

    /**
     * Set the label wrapper classes in detail panel that should be applied instead of default ones.
     *
     * @param  string  $classes
     * @return $this
     */
    public function panelLabelClasses($classes)
    {
        $this->panelLabelClasses = $classes;

        return $this;
    }

    /**
     * Add classes to the base label classes for detail view.
     *
     * @param  string  $classes
     * @return $this
     */
    public function addPanelLabelClasses($classes)
    {
        $this->panelLabelClasses = "{$this->panelLabelClasses} {$classes}";

        return $this;
    }

    /**
     * Set the excerpt classes that should be applied instead of default ones.
     *
     * @param  string  $classes
     * @return $this
     */
    public function excerptClasses($classes)
    {
        $this->excerptClasses = $classes;

        return $this;
    }

    /**
     * Add classes to the base excerpt classes.
     *
     * @param  string  $classes
     * @return $this
     */
    public function addExcerptClasses($classes)
    {
        $this->excerptClasses = "{$this->excerptClasses} {$classes}";

        return $this;
    }

    /**
     * Indicate that the label should be hidden in forms.
     *
     * @return $this
     */
    public function hideLabelInForms()
    {
        return $this->withMeta(['hideLabelInForms' => true]);
    }

    /**
     * Indicate that the label should be hidden in detail view.
     *
     * @return $this
     */
    public function hideLabelInDetail()
    {
        return $this->withMeta(['hideLabelInDetail' => true]);
    }

    /**
     * Sets the input placeholder
     *
     * @param  string  $placeholder
     * @return $this
     */
    public function placeholder($placeholder)
    {
        return $this->withMeta(['placeholder' => $placeholder]);
    }

    /**
     * Set the input as disabled.
     *
     * @return $this
     */
    public function readOnly()
    {
        return $this->withMeta(['readOnly' => true]);
    }

    /**
     * Set the input as disabled on create view.
     *
     * @return $this
     */
    public function readOnlyOnCreate()
    {
        return $this->withMeta(['readOnlyOnCreate' => true]);
    }

    /**
     * Set the input as disabled on update view.
     *
     * @return $this
     */
    public function readOnlyOnUpdate()
    {
        return $this->withMeta(['readOnlyOnUpdate' => true]);
    }

    /**
     * Show or hide the field based on other field value
     *
     * @param  array  $field
     * @return $this
     */
    public function onlyWhen($field)
    {
        return $this->withMeta(['onlyWhen' => $field]);
    }

    /**
     * Get additional meta information to merge with the element payload.
     *
     * @return array
     */
    public function meta()
    {
        return array_merge([
            'wrapperClasses' => $this->wrapperClasses,
            'inputClasses' => $this->inputClasses,
            'fieldClasses' => $this->fieldClasses,
            'panelFieldClasses' => $this->panelFieldClasses,
            'labelClasses' => $this->labelClasses,
            'panelLabelClasses' => $this->panelLabelClasses,
            'excerptClasses' => $this->excerptClasses,
        ], $this->meta);
    }
}
