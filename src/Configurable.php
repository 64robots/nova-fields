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
}
