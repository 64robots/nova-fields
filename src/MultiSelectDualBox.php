<?php

namespace R64\NovaFields;

use Laravel\Nova\Fields\Field;
use R64\NovaFields\Traits\MultiselectBelongsToSupport;

class MultiSelectDualBox extends Field
{
    use MultiselectBelongsToSupport,Configurable;

    public $component = 'nova-fields-multi-select-dual-box';

    /**
     * The base input classes of the field.
     *
     * @var string
     */
    public $inputClasses = 'multiselect';

    /**
     * The base index classes of the field.
     *
     * @var string
     */
    public $indexClasses = 'whitespace-no-wrap';

    protected $options;

    /**
     * Sets the options available for select.
     *
     * @param  array|callable
     * @return \OptimistDigital\MultiselectField\Multiselect
     **/
    public function options($options = [])
    {
        if (is_callable($options)) $options = call_user_func($options);
        $options = collect($options ?? []);

        $isOptionGroup = $options->contains(function ($label, $value) {
            return is_array($label);
        });

        if ($isOptionGroup) {
            $_options = $options
                ->map(function ($value, $key) {
                    return collect($value + ['value' => $key]);
                })
                ->groupBy('group')
                ->map(function ($value, $key) {
                    return ['label' => $key, 'values' => $value->map->only(['label', 'value'])->toArray()];
                })
                ->values()
                ->toArray();

            return $this->withMeta(['options' => $_options]);
        }


        return $this->withMeta([
            'options' => $options->map(function ($label, $value) {
                return ['label' => $label, 'value' => $value];
            })->values()->all(),
        ]);
    }

    public function basedOnOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions($parameters = [])
    {
        $options = call_user_func($this->options, $parameters);

        $result = [];
        foreach ($options as $key => $option) {
            $result[] = [
                'label' => $option,
                'value' => $key,
            ];
        }
        return $result;
    }

    /**
     * Display the field as raw HTML using Vue.
     *
     * @return $this
     */

    public function setLeftPlaceholder($placeholder)
    {
        return $this->withMeta(['leftPlaceholder' => $placeholder]);
    }

    public function setRightPlaceholder($placeholder)
    {
        return $this->withMeta(['rightPlaceholder' => $placeholder]);
    }

    public function setLeftHeader($header)
    {
        return $this->withMeta(['leftHeader' => $header]);
    }

    public function setRightHeader($header)
    {
        return $this->withMeta(['rightHeader' => $header]);
    }

    public function setLeftEmptyMessage($message)
    {
        return $this->withMeta(['leftEmptyMessage' => $message]);
    }

    public function setRightEmptyMessage($message)
    {
        return $this->withMeta(['rightEmptyMessage' => $message]);
    }

    public function confirmationOnUpdate($onUpdate = true){
        return $this->withMeta(['confirmationOnUpdate' => $onUpdate]);
    }

    public function confirmationOnCreate($onCreate = true){
        return $this->withMeta(['confirmationOnCreate' => $onCreate]);
    }

    public function confirmation($confirmation = true){
        return $this->withMeta(['confirmation' => $confirmation]);
    }

    public function confirmationMessage($confirmationMessage){
        return $this->withMeta(['confirmationMessage' => $confirmationMessage]);
    }

    public function basedOn($attribute)
    {
        $this->withMeta(['parentAttribute' => $attribute]);
        return $this;
    }
}
