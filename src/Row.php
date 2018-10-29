<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Contracts\Resolvable;
use Laravel\Nova\Http\Requests\NovaRequest;

class Row extends Field
{
    use Configurable, HasChilds;

    /**
     * The base input classes of the field.
     *
     * @var string
     */
    public $inputClasses = '';

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-row';

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = false;

    /**
     * The child fields.
     *
     * @var array
     */
    public $fields = [];

    /**
     * Create a new JSON field.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  mixed|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $fields, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->withMeta(['fields' => $fields]);
    }

    /**
     * Indicate that the heading should be hidden.
     *
     * @return $this
     */
    public function hideHeading()
    {
        return $this->withMeta(['hideHeading' => true]);
    }

    /**
     * Set text for Add Row button.
     * @param  string  $name
     * @return $this
     */
    public function addRowText($text)
    {
        return $this->withMeta(['addRowText' => $text]);
    }

    /**
     * Generate field-specific validation rules.
     * 
     * @param  array  $rules
     * @return array
     */
    protected function generateRules($rules)
    {
        return collect($rules)->mapWithKeys(function ($rules, $key) {
            return [$this->attribute . '.*.' . $key => $rules];
        })->toArray();
    }

    /**
     * Get the validation rules for this field.
     * 
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function getRules(NovaRequest $request)
    {
        $result = [];

        foreach ($this->meta['fields'] as $field) {
            $fieldRules = $this->generateRules($field->getRules($request));
            $result = array_merge_recursive($result, $fieldRules);
        }

        return array_merge_recursive(
            parent::getRules($request), $result
        );
    }

    /**
     * Get the creation rules for this field.
     * 
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array|string
     */
    public function getCreationRules(NovaRequest $request)
    {
        $result = [];

        foreach ($this->meta['fields'] as $field) {
            $rules = $this->generateRules($field->getCreationRules($request));
            $result = array_merge_recursive($result, $rules);
        }

        return array_merge_recursive(
            parent::getCreationRules($request), $result
        );
    }

    /**
     * Get the update rules for this field.
     * 
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function getUpdateRules(NovaRequest $request)
    {
        $result = [];

        foreach ($this->meta['fields'] as $field) {
            $rules = $this->generateRules($field->getUpdateRules($request));
            $result = array_merge_recursive($result, $rules);
        }

        return array_merge_recursive(
            parent::getUpdateRules($request), $result
        );
    }
}
