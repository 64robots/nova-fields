<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\ID as NovaID;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Util;
use R64\NovaFields\Configurable;

class ID extends NovaID
{
    use Configurable;
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-id';

    /**
     * The field's resolved pivot value.
     *
     * @var mixed
     */
    public $pivotValue = null;

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
    public $indexClasses = 'whitespace-no-wrap';

    /**
     * Create a new field.
     *
     * @param string|null $name
     * @param string|null $attribute
     * @param mixed|null $resolveCallback
     * @return void
     */
    public function __construct($name = null, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name ?? 'ID', $attribute, $resolveCallback);
    }

    /**
     * Create a new, resolved ID field for the given resource.
     *
     * @param \Laravel\Nova\Resource $resource
     * @return static
     */
    public static function forResource($resource)
    {
        $model = $resource->model();

        $methods = collect(['fieldsForIndex', 'fieldsForDetail'])
            ->filter(function ($method) use ($resource) {
                return method_exists($resource, $method);
            })->all();

        $field = transform(
            $resource->buildAvailableFields(app(NovaRequest::class), $methods)
                ->whereInstanceOf(self::class)
                ->first(),
            function ($field) use ($model) {
                return tap($field)->resolve($model);
            },
            function () use ($model) {
                return !is_null($model) && $model->exists ? static::forModel($model) : null;
            }
        );

        return empty($field->value) && $field->nullable !== true ? null : $field;
    }

    /**
     * Create a new, resolved ID field for the given model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return static
     */
    public static function forModel($model)
    {
        return tap(static::make('ID', $model->getKeyName()), function ($field) use ($model) {
            $value = $model->getKey();

            if (is_int($value) && $value >= 9007199254740991) {
                $field->asBigInt();
            }

            $field->resolve($model);
        });
    }

    /**
     * Resolve the given attribute from the given resource.
     *
     * @param mixed $resource
     * @param string $attribute
     * @return mixed
     */
    protected function resolveAttribute($resource, $attribute)
    {
        if (!is_null($resource)) {
            $pivotValue = optional($resource->pivot)->getKey();

            if (is_int($pivotValue) || is_string($pivotValue)) {
                $this->pivotValue = $pivotValue;
            }
        }

        return Util::safeInt(
            parent::resolveAttribute($resource, $attribute)
        );
    }

    /**
     * Resolve a BIGINT ID field as a string for compatibility with JavaScript.
     *
     * @return $this
     */
    public function asBigInt()
    {
        $this->resolveCallback = function ($id) {
            return (string)$id;
        };

        return $this;
    }

    /**
     * Hide the ID field from the Nova interface but keep it available for operations.
     *
     * @return $this
     */
    public function hide()
    {
        $this->showOnIndex = false;
        $this->showOnDetail = false;
        $this->showOnCreation = false;
        $this->showOnUpdate = false;

        return $this;
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), array_filter([
            'pivotValue' => $this->pivotValue ?? null,
        ]));
    }
}
