<?php

namespace R64\NovaFields;

use Laravel\Nova\Http\Requests\NovaRequest;
use R64\NovaFields\Configurable;
use Laravel\Nova\Fields\Field as NovaField;

class BooleanGroup extends NovaField
{
    use Configurable;

    /**
     * The base input classes of the field.
     *
     * @var string
     */
    public $inputClasses = 'mt-2';

    /**
     * The base index classes of the field.
     *
     * @var string
     */
    public $indexClasses = 'text-center';

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-fields-boolean-group';

    /**
     * The text alignment for the field's text in tables.
     *
     * @var string
     */
    public $textAlign = 'center';

    /**
     * The text to be used when there are no booleans to show.
     *
     * @var string
     */
    public $noValueText = 'No Data';

    /**
     * The options for the field.
     *
     * @var array
     */
    public $options;

    /**
     * @var bool
     */
    public $hideFalseValues;

    /**
     * @var bool
     */
    public $hideTrueValues;

    /**
     * Set the options for the field.
     *
     * @param  array|\Closure|\Illuminate\Support\Collection
     * @return $this
     */
    public function options($options)
    {
        if (is_callable($options)) {
            $options = $options();
        }

        $this->options = with(collect($options), function ($options) {
            return $options->map(function ($label, $name) use ($options) {
                return $options->isAssoc()
                    ? ['label' => $label, 'name' => $name]
                    : ['label' => $label, 'name' => $label];
            })->values()->all();
        });

        return $this;
    }

    /**
     * Whether false values should be hidden on the index.
     *
     * @return $this
     */
    public function hideFalseValues()
    {
        $this->hideTrueValues = false;
        $this->hideFalseValues = true;

        return $this;
    }

    /**
     * Whether true values should be hidden on the index.
     *
     * @return $this
     */
    public function hideTrueValues()
    {
        $this->hideTrueValues = true;
        $this->hideFalseValues = false;

        return $this;
    }

    /**
     * Set the text to be used when there are no booleans to show.
     *
     * @param  string  $text
     * @return $this
     */
    public function noValueText($text)
    {
        $this->noValueText = $text;

        return $this;
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
        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = json_decode($request[$requestAttribute], true);
        }
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'hideTrueValues' => $this->hideTrueValues,
            'hideFalseValues' => $this->hideFalseValues,
            'options' => $this->options,
            'noValueText' => __($this->noValueText),
        ]);
    }
}
