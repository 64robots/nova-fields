<?php

namespace R64\NovaFields;

use Laravel\Nova\Contracts\Resolvable;
use Laravel\Nova\Fields\Expandable;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class Row extends Field
{
    use Configurable, HasChilds, Expandable;

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
     * Create a new Row field.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  mixed|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $fields, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->fields = collect($fields);
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
     * Set the maximum of rows that can be added.
     *
     * @return $this
     */
    public function maxRows($rows)
    {
        return $this->withMeta(['maxRows' => $rows]);
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
     * Resolve the field's value.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function resolve($resource, $attribute = null)
    {
        $attribute = $attribute ?? $this->attribute;

        $value = $resource->{$attribute} ?? null;

        $value = is_object($value) || is_array($value) ? $value : json_decode($value);

        $fields = $this->fields->whereInstanceOf(Resolvable::class)->reduce(function ($values, $field) {
            return $values->map(function ($row) use ($field) {
                $key = $field->attribute;
                $cb = $field->resolveCallback;

                if (isset($row->{$key})) {
                    $row->{$key} = $cb ? call_user_func($cb, $row->{$key}) : $row->{$key};
                }

                if (property_exists($field, 'computeCallback') && $field->computeCallback) {
                    $row->{$key} = call_user_func($field->computeCallback, $row);
                }

                return $row;
            });
        }, collect($value));

        if (!$this->resolveCallback) {
            $this->resolveCallback = function () use ($fields) {
                return $fields->toArray();
            };
        }

        $this->withMeta(['fields' => $this->fields]);

        parent::resolve($resource, $attribute);
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
            parent::getRules($request),
            $result
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
            parent::getCreationRules($request),
            $result
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
            parent::getUpdateRules($request),
            $result
        );
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'sanitizedAttribute' => str_plural(kebab_case($this->attribute)),
            'shouldShow' => $this->shouldBeExpanded(),
        ]);
    }
}
