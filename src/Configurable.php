<?php

namespace R64\NovaFields;

use Config;
use Illuminate\Support\Str;

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
     * The base field classes for the detail view.
     *
     * @var string
     */
    public $panelFieldClasses = 'w-3/4 py-4';

    /**
     * The base wrapper classes for the detail view.
     *
     * @var string
     */
    public $panelWrapperClasses = 'flex border-b border-40';

    /**
     * The base label classes for the detail view.
     *
     * @var string
     */
    public $indexLinkClasses = 'no-underline dim text-primary font-bold';

    /**
     * The base excerpt classes.
     *
     * @var string
     */
    public $excerptClasses = 'cursor-pointer dim inline-block text-primary font-bold';

    /**
     * List of property and classes to add with optional conditional callbacks
     *
     * @var array
     */
    public $addClasses = [];

    /**
     * List of property and classes to remove with optional conditional callbacks
     *
     * @var array
     */
    public $removeClasses = [];

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
     * Set the index classes that should be applied instead of default ones.
     *
     * @param  string  $classes
     * @return $this
     */
    public function indexClasses($classes)
    {
        $this->indexClasses = $classes;

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
     * Set the field classes in detail panel that should be applied instead of default ones.
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
     * Set the wrapper in detail panel that should be applied instead of default ones.
     *
     * @param  string  $classes
     * @return $this
     */
    public function panelWrapperClasses($classes)
    {
        $this->panelWrapperClasses = $classes;

        return $this;
    }

    /**
     * Set the link classes in index view that should be applied instead of the default.
     *
     * @param  string  $classes
     * @return $this
     */
    public function indexLinkClasses($classes)
    {
        $this->indexLinkClasses = $classes;

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
     * Specify that the index view should show as a link to the resource
     *
     * @return $this
     */
    public function showLinkInIndex()
    {
        return $this->withMeta(['showLinkInIndex' => true]);
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
     * Overriding the base method in order to grab the model ID.
     *
     * @param mixed  $resource  The resource class
     * @param string $attribute The attribute of the resource
     *
     * @return mixed
     */
    protected function resolveAttribute($resource, $attribute)
    {
        $this->setResourceId(data_get($resource, 'id'));
        return parent::resolveAttribute($resource, $attribute);
    }
    /**
     * Sets the
     *
     * @param mixed $id The ID of the resource model. Also sets the base URL based on the Nova config
     *
     * @return void
     */
    protected function setResourceId($id)
    {
        $path = Config::get('nova.path');
        // If the path is the site route, prevent a double-slash
        if ('/' === $path) {
            $path = '';
        }
        return $this->withMeta(['id' => $id, 'nova_path' => $path]);
    }

    /**
     * Dynamically add or remove classes.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        preg_match('/(add|remove)(.+)/', $method, $matches);

        if (count($matches) != 3) {
            return parent::__call($method, $parameters);
        }

        $action = $matches[1];
        $property = Str::camel($matches[2]);
        $classes = $parameters[0];
        $callback = $parameters[1] ?? null;

        if (!property_exists($this, $property)) {
            return $this;
        }

        if ($action === 'add') {
            $this->addClasses[] = [$property, $classes, $callback];
        }

        if ($action === 'remove') {
            $this->removeClasses[] = [$property, $classes, $callback];
        }

        return $this;
    }

    /**
     * Add classes to a property
     *
     * @param string $property
     * @param string $classes
     * @param ?callable $callback
     */
    private function addClassesToProperty($property, $classes, $callback): void
    {
        if (!is_null($callback) && !$callback($this->resource)) {
            return;
        }

        $this->$property = "{$this->$property} {$classes}";
    }

    /**
     * Remove classes from a property
     *
     * @param string $property
     * @param string $classes
     * @param ?callable $callback
     */
    private function removeClassesFromProperty($property, $classes, $callback): void
    {
        if (!is_null($callback) && !$callback($this->resource)) {
            return;
        }

        $this->$property = str_replace(preg_split('/[\s,]+/', $classes), '', $this->$property);
    }

    /**
     * Get additional meta information to merge with the element payload.
     *
     * @return array
     */
    public function meta()
    {
        collect($this->addClasses)->each(function ($classToAdd) {
            $this->addClassesToProperty($classToAdd[0], $classToAdd[1], $classToAdd[2]);
        });

        collect($this->removeClasses)->each(function ($classToRemove) {
            $this->removeClassesFromProperty($classToRemove[0], $classToRemove[1], $classToRemove[2]);
        });

        return array_merge([
            'wrapperClasses' => $this->wrapperClasses,
            'indexClasses' => $this->indexClasses,
            'inputClasses' => $this->inputClasses,
            'fieldClasses' => $this->fieldClasses,
            'labelClasses' => $this->labelClasses,
            'panelLabelClasses' => $this->panelLabelClasses,
            'panelFieldClasses' => $this->panelFieldClasses,
            'panelWrapperClasses' => $this->panelWrapperClasses,
            'indexLinkClasses' => $this->indexLinkClasses,
            'excerptClasses' => $this->excerptClasses,
        ], $this->meta);
    }
}
