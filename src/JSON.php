<?php

namespace R64\NovaFields;

use Laravel\Nova\Panel;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
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
     * The base index classes of the panels title.
     *
     * @var string
     */
    public $panelTitleClasses = 'text-90 font-normal text-2xl flex-no-shrink py-4';

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

        $this->fields = collect();

        foreach ($fields as $field) {
            if ($field instanceof Panel) {
                collect($field->data)->each(function ($f) {
                    $this->fields->push($f);
                });
            } else {
                $this->fields->push($field);
            }
        }
    }

    /**
     * Set the panel title classes that should be applied instead of default ones.
     *
     * @param  string  $classes
     * @return $this
     */
    public function panelTitleClasses($classes)
    {
        $this->panelTitleClasses = $classes;

        return $this;
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

        $value = data_get($resource, $attribute);

        $this->value = is_object($value) || is_array($value) ? $value : json_decode($value);

        $this->fields->whereInstanceOf(Date::class)->each(function ($dateField) {
            $dateField->resolveCallback = function ($value) {
                return \Carbon\Carbon::parse($value)->format('Y-m-d');
            };
        });

        $this->fields->whereInstanceOf(DateTime::class)->each(function ($dateField) {
            $dateField->resolveCallback = function ($value) {
                return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
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
            $field->fillInto($request, $model, $attribute . '->' . $field->attribute, $attribute . '.' . $field->attribute);
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
            return [$this->attribute . ($key ? '.' . $key : '') => $rules];
        })->toArray();
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

        $value = data_get($resource, $attribute);

        $this->value = is_object($value) || is_array($value) ? $value : json_decode($value);

        $this->fields->whereInstanceOf(Resolvable::class)->each->resolveForDisplay($this->value);

        parent::resolve($resource, $attribute);
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'panelTitleClasses' => $this->panelTitleClasses,
            'fields' => $this->fields,
        ]);
    }
}
