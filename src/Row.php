<?php

namespace R64\NovaFields;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Expandable;
use Laravel\Nova\Contracts\Resolvable;
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
     * The base heading classes of the field.
     *
     * @var string
     */
    public $headingClasses = 'flex text-80 py-2';

    /**
     * The base item wrapper classes of the field.
     *
     * @var string
     */
    public $itemWrapperClasses = 'flex border-40 border';

    /**
     * The base heading classes of the field.
     *
     * @var string
     */
    public $deleteButtonClasses = 'flex items-center justify-center bg-danger text-white p-2 m-2 w-6 h-6 rounded-full cursor-pointer font-bold';

    /**
     * The base classes for the Add Row button.
     *
     * @var string
     */
    public $addRowButtonClasses = 'btn btn-primary p-3 rounded cursor-pointer mt-3';

    /**
     * The base classes for the Row wrapper.
     *
     * @var string
     */
    public $rowWrapperClasses = 'flex items-center border-40 border relative';

    /**
     * The base classes for the Sum wrapper.
     *
     * @var string
     */
    public $sumWrapperClasses = 'flex items-center justify-end w-full p-2';

    /**
     * The base classes for the Sum field.
     *
     * @var string
     */
    public $sumFieldClasses = 'text-80';

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

        $this->fields = $this->prepareFields($fields);
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
     * Determine if the row heading should be hidden when empty rows.
     *
     * @return $this
     */
    public function hideHeadingWhenEmpty()
    {
        return $this->withMeta(['hideHeadingWhenEmpty' => true]);
    }

    /**
     * Shows a sum of the value of the field.
     *
     * @return $this
     */
    public function sum($field)
    {
        return $this->withMeta(['sum' => $field]);
    }

    /**
     * Determine if the sum field should be hidden when empty rows.
     *
     * @return $this
     */
    public function hideSumWhenEmpty()
    {
        return $this->withMeta(['hideSumWhenEmpty' => true]);
    }

    /**
     * Prepopulate one empty row when the collection is empty.
     *
     * @return $this
     */
    public function prepopulateRowWhenEmpty()
    {
        return $this->withMeta(['prepopulateRowWhenEmpty' => true]);
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
     * Sets the classes to be displayed in row heading
     *
     * @param  string  $classes
     * @return $this
     */
    public function headingClasses($classes)
    {
        $this->headingClasses = $classes;

        return $this;
    }

    /**
     * Sets the classes to be displayed wrapping the item
     *
     * @param  string  $classes
     * @return $this
     */
    public function itemWrapperClasses($classes)
    {
        $this->itemWrapperClasses = $classes;

        return $this;
    }

    /**
     * Sets the classes to be displayed in delete row button
     *
     * @param  string  $classes
     * @return $this
     */
    public function deleteButtonClasses($classes)
    {
        $this->deleteButtonClasses = $classes;

        return $this;
    }

    /**
     * Sets the classes to be displayed in sum wrapper
     *
     * @param  string  $classes
     * @return $this
     */
    public function sumWrapperClasses($classes)
    {
        $this->sumWrapperClasses = $classes;

        return $this;
    }

    /**
     * Sets the classes to be displayed in sum field
     *
     * @param  string  $classes
     * @return $this
     */
    public function sumFieldClasses($classes)
    {
        $this->sumFieldClasses = $classes;

        return $this;
    }

    /**
     * Sets the classes to be displayed in Add Row button
     *
     * @param  string  $classes
     * @return $this
     */
    public function addRowButtonClasses($classes)
    {
        $this->addRowButtonClasses = $classes;

        return $this;
    }

    /**
     * Sets the classes to be displayed in Row wrapper
     *
     * @param  string  $classes
     * @return $this
     */
    public function rowWrapperClasses($classes)
    {
        $this->rowWrapperClasses = $classes;

        return $this;
    }

    /**
     * Uses wrapper classes in heading instead of field classes
     *
     * @param  string  $classes
     * @return $this
     */
    public function useWrapperClassesInHeading()
    {
        return $this->withMeta(['useWrapperClassesInHeading' => true]);
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

                if ($field instanceof Date) {
                    $cb = function ($value) {
                        return \Carbon\Carbon::parse($value)->format('Y-m-d');
                    };
                };

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
     * Prepare subfields for Row.
     *
     * @param  array  $fields
     * @return Collection
     */
    protected function prepareFields($fields)
    {
        return collect($fields)->each(function($field) {
            if($field->isReadonly(app(NovaRequest::class))) {
                $field->withMeta(['readOnly' => true]);
            }
            if (!$field->showOnIndex) {
                $field->withMeta(['hideFromIndex' => true]);
            }
            if (!$field->showOnCreation) {
                $field->withMeta(['hideWhenCreating' => true]);
            }
            if (!$field->showOnUpdate) {
                $field->withMeta(['hideWhenUpdating' => true]);
            }
            if (!$field->showOnDetail) {
                $field->withMeta(['hideFromDetail' => true]);
            }
        });
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'sanitizedAttribute' => Str::plural(Str::kebab($this->attribute)),
            'shouldShow' => $this->shouldBeExpanded(),
            'headingClasses' => $this->headingClasses,
            'itemWrapperClasses' => $this->itemWrapperClasses,
            'deleteButtonClasses' => $this->deleteButtonClasses,
            'addRowButtonClasses' => $this->addRowButtonClasses,
            'rowWrapperClasses' => $this->rowWrapperClasses,
            'sumWrapperClasses' => $this->sumWrapperClasses,
            'sumFieldClasses' => $this->sumFieldClasses,
        ]);
    }
}
