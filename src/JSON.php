<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Field;
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
        $request->merge([
            $requestAttribute => json_decode($request[$requestAttribute], true)
        ]);

        $this->fields->each(function ($field) use ($request, $model, $attribute) {
            $field->fillInto($request, $model, $attribute.'->'.$field->attribute, $attribute.'.'.$field->attribute);
        });
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
}
