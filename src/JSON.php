<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Contracts\Resolvable;
use Laravel\Nova\Http\Requests\NovaRequest;

class JSON extends Field
{
    use Configurable, HasChilds;

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
    public $indexClasses = 'flex';

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-json';

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
     * @param  string  $fields
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
     * Whether the field should be shown on the index.
     *
     * @return $this
     */
    public function showOnIndex()
    {
        $this->showOnIndex = true;

        return $this;
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

        $value = $resource->{$attribute};

        $this->value = is_object($value) || is_array($value) ? $value : json_decode($value);

        $this->fields->whereInstanceOf(Date::class)->each(function ($dateField) {
            $dateField->resolveCallback = function ($value) {
                return \Carbon\Carbon::parse($value)->format('Y-m-d');
            };
        });

        $this->fields->whereInstanceOf(Resolvable::class)->each->resolve($this->value);

        $this->withMeta(['fields' => $this->fields]);

        parent::resolve($resource, $attribute);
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     * @return void
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $this->fields->each(function ($field) use ($request, $model, $attribute) {
            $field->fillInto($request, $model, $attribute.'->'.$field->attribute, $attribute.'.'.$field->attribute);
        });
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
            return [$this->attribute . '.' . $key => $rules];
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

        foreach ($this->fields as $field) {
            $rules = $this->generateRules($field->getRules($request));

            $result = array_merge_recursive($result, $rules);
        }

        return $result;
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

        foreach ($this->fields as $field) {
            $rules = $this->generateRules($field->getCreationRules($request));

            $result = array_merge_recursive($result, $rules);
        }

        return $result;
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

        foreach ($this->fields as $field) {
            $rules = $this->generateRules($field->getUpdateRules($request));

            $result = array_merge_recursive($result, $rules);
        }

        return $result;
    }

    /**
     * Resolve the field's value for display.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function resolveForDisplay($resource, $attribute = null)
    {
        $attribute = $attribute ?? $this->attribute;

        $value = $resource->{$attribute};

        $this->value = is_object($value) || is_array($value) ? $value : json_decode($value);

        $this->fields->whereInstanceOf(Resolvable::class)->each->resolveForDisplay($this->value);

        parent::resolve($resource, $attribute);
    }

    /**
     * Whether the fields within the JSON should be 'flattened'.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function flatten($value = true)
    {
        return $this->withMeta([
            'flatten' => $value
        ]);
    }
}
