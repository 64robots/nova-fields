<?php

namespace R64\NovaFields;

trait Configurable
{
    /**
     * Set the container classes that should be applied instead of default ones.
     *
     * @param  string  $classes
     * @return $this
     */
    public function wrapperClasses($classes)
    {
        return $this->withMeta(['wrapperClasses' => $classes]);
    }

    /**
     * Set the input classes that should be applied instead of default ones.
     *
     * @param  string  $classes
     * @return $this
     */
    public function inputClasses($classes)
    {
        return $this->withMeta(['inputClasses' => $classes]);
    }

    /**
     * Set the field wrapper classes that should be applied instead of default ones.
     *
     * @param  string  $classes
     * @return $this
     */
    public function fieldClasses($classes)
    {
        return $this->withMeta(['fieldClasses' => $classes]);
    }

    /**
     * Set the field wrapper classes in detail panel that should be applied instead of default ones.
     *
     * @param  string  $classes
     * @return $this
     */
    public function panelFieldClasses($classes)
    {
        return $this->withMeta(['panelFieldClasses' => $classes]);
    }

    /**
     * Set the label wrapper classes that should be applied instead of default ones.
     *
     * @param  string  $classes
     * @return $this
     */
    public function labelClasses($classes)
    {
        return $this->withMeta(['labelClasses' => $classes]);
    }

    /**
     * Set the label wrapper classes in detail panel that should be applied instead of default ones.
     *
     * @param  string  $classes
     * @return $this
     */
    public function panelLabelClasses($classes)
    {
        return $this->withMeta(['panelLabelClasses' => $classes]);
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
     * Set the excerpt classes that should be applied instead of default ones.
     *
     * @param  string  $classes
     * @return $this
     */
    public function excerptClasses($classes)
    {
        return $this->withMeta(['excerptClasses' => $classes]);
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
}
